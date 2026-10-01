<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DailyCheckin;
use App\Models\Membership;
use App\Models\Mission;
use App\Models\SpinResult;
use App\Services\MembershipService;
use App\Services\RewardService;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function __construct(
        protected MembershipService $membershipService,
        protected RewardService $rewardService,
    ) {}

    public function index()
    {
        $user       = auth()->user();
        $membership = $user->membership ?? $this->membershipService->createForNewUser($user);

        $missions          = Mission::where('is_active', true)->get();
        $completedMissions = $user->userMissions()->pluck('mission_id')->toArray();

        foreach ($missions as $mission) {
            $mission->current_progress = 0;
            switch ($mission->trigger_type) {
                case 'first_booking':
                    $mission->current_progress = \App\Models\Booking::where('user_id', $user->id)->where('status', 'completed')->count();
                    break;
                case 'spending_milestone':
                    $mission->current_progress = \App\Models\Booking::where('user_id', $user->id)
                        ->where('status', 'completed')
                        ->max('service_price_at_booking') ?? 0;
                    break;
                case 'booking_count_monthly':
                    $mission->current_progress = \App\Models\Booking::where('user_id', $user->id)
                        ->where('status', 'completed')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();
                    break;
                case 'referral':
                    $mission->current_progress = \App\Models\User::where('referred_by', $user->id)
                        ->whereHas('bookings', function($q) {
                            $q->where('status', 'completed');
                        })->count();
                    break;
                case 'checkin_streak':
                    $mission->current_progress = \App\Models\DailyCheckin::getStreakForUser($user);
                    break;
            }
        }

        $todayCheckin      = DailyCheckin::where('user_id', $user->id)
                                ->where('checked_in_date', now()->toDateString())
                                ->first();

        $currentStreak     = DailyCheckin::getStreakForUser($user);

        $checkinHistory    = $user->dailyCheckins()
                                ->orderByDesc('checked_in_date')
                                ->take(30)
                                ->get();

        $recentTransactions = $user->pointTransactions()
                                ->orderByDesc('created_at')
                                ->take(10)
                                ->get();

        $spinsToday = SpinResult::where('user_id', $user->id)
                        ->whereDate('created_at', now()->toDateString())
                        ->count();

        $spinSegments = RewardService::getSpinSegments();

        $voucherCatalogue = [
            ['points' => 200,  'value' => 10, 'label' => __('rewards.cat_rm10_cash_voucher'), 'icon' => 'fa-ticket', 'min_tier' => 'bronze'],
            ['points' => 500,  'value' => 25, 'label' => __('rewards.cat_rm25_cash_voucher'), 'icon' => 'fa-ticket', 'min_tier' => 'silver'],
            ['points' => 1000, 'value' => 50, 'label' => __('rewards.cat_rm50_vip_voucher'), 'icon' => 'fa-ticket', 'min_tier' => 'gold'],
        ];

        // Fetch User Vouchers for Tab 3 (My Rewards)
        $availableVouchers = \App\Models\Voucher::where('user_id', $user->id)
            ->where('status', 'available')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at')
            ->get();

        $usedVouchers = \App\Models\Voucher::where('user_id', $user->id)
            ->where('status', 'used')
            ->orderByDesc('used_at')
            ->get();

        $expiredVouchers = \App\Models\Voucher::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('status', 'expired')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'available')
                         ->where('expires_at', '<', now());
                  });
            })
            ->orderByDesc('expires_at')
            ->get();

        return view('customer.rewards.index', compact(
            'user', 'membership', 'missions', 'completedMissions',
            'todayCheckin', 'currentStreak', 'checkinHistory',
            'recentTransactions', 'spinsToday', 'spinSegments',
            'voucherCatalogue', 'availableVouchers', 'usedVouchers', 'expiredVouchers'
        ));
    }

    public function tiers()
    {
        $user = auth()->user();
        $membership = $user->membership ?? $this->membershipService->createForNewUser($user);

        return view('customer.rewards.tiers', compact('membership'));
    }

    public function getQrCode()
    {
        $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'admin.memberships.scan_resolve',
            now()->addSeconds(60),
            ['user' => auth()->id()]
        );

        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($url);

        return response()->json([
            'qr_url' => $qrApiUrl
        ]);
    }

    public function checkin(Request $request)
    {
        $result = $this->rewardService->processCheckin(auth()->user());

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success'] && !empty($result['streak_bonus'])) {
            session()->flash('confetti', true);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function redeemPoints(Request $request)
    {
        $request->validate([
            'points_cost' => 'required|integer|in:200,500,1000',
        ]);

        $result = $this->rewardService->redeemPointsForVoucher(
            auth()->user(),
            (int) $request->points_cost
        );

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            session()->flash('confetti', true);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function redeemCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $result = $this->rewardService->redeemPromoCode(auth()->user(), $request->code);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            session()->flash('confetti', true);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function applyReferral(Request $request)
    {
        $request->validate([
            'referral_code' => ['required', 'string'],
        ]);

        $user = auth()->user();

        if (!is_null($user->referred_by)) {
            return back()->with('error', __('dashboard.ref_already_used'));
        }

        $code = strtoupper(trim($request->referral_code));

        $membership = \App\Models\Membership::where('referral_code', $code)->first();

        if (!$membership) {
            return back()->with('error', __('dashboard.ref_invalid'));
        }

        if ((int) $membership->user_id === (int) $user->id) {
            return back()->with('error', __('dashboard.ref_self'));
        }

        $user->referred_by = $membership->user_id;
        $user->save();

        return back()->with('success', __('dashboard.ref_success'));
    }

    public function spinWheel(Request $request)
    {
        $result = $this->rewardService->spinWheel(auth()->user());

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            session()->flash('confetti', true);
            return redirect()->route('rewards.index')
                ->with('success', $result['message'])
                ->with('spin_result', $result);
        }

        return back()->with('error', $result['message']);
    }
}
