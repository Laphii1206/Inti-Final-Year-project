<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\Voucher;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMembershipController extends Controller
{
    public function __construct(protected MembershipService $membershipService) {}

    public function index(Request $request)
    {
        $query = User::where('role', User::ROLE_CUSTOMER)
            ->with('membership')
            ->withCount('bookings');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($tier = $request->get('tier')) {
            $query->whereHas('membership', fn($q) => $q->where('tier', $tier));
        }

        $members = $query->paginate(20)->withQueryString();

        $stats = [
            'total'  => User::where('role', User::ROLE_CUSTOMER)->count(),
            'bronze' => Membership::where('tier', 'bronze')->count(),
            'silver' => Membership::where('tier', 'silver')->count(),
            'gold'   => Membership::where('tier', 'gold')->count(),
        ];

        return view('admin.memberships.index', compact('members', 'stats'));
    }

    public function scanner()
    {
        return view('admin.memberships.scanner');
    }

    public function resolveScan(User $user)
    {
        return redirect()->route('admin.memberships.show', $user)
            ->with('success', 'Customer scanned successfully!');
    }

    public function show(User $user)
    {
        $membership = $user->membership ?? $this->membershipService->createForNewUser($user);

        $transactions = PointTransaction::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        $vouchers = $user->vouchers()->orderByDesc('created_at')->get();

        return view('admin.memberships.show', compact('user', 'membership', 'transactions', 'vouchers'));
    }

    public function adjustPoints(Request $request, User $user)
    {
        $request->validate([
            'points'      => 'required|integer|not_in:0',
            'description' => 'required|string|max:255',
        ]);

        $points = (int) $request->points;

        if ($points > 0) {
            $this->membershipService->addPoints($user, $points, $request->description, 'admin_adjust');
            return back()->with('success', "Added {$points} points to {$user->name}.");
        } else {
            $deducted = $this->membershipService->deductPoints($user, abs($points), $request->description, 'admin_adjust');
            if (!$deducted) {
                return back()->with('error', "Insufficient points balance. Could not deduct " . abs($points) . " points from {$user->name}.");
            }
            return back()->with('success', "Deducted " . abs($points) . " points from {$user->name}.");
        }
    }

    public function promoCodes(Request $request)
    {
        $codes = Voucher::whereNull('user_id')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.memberships.promo-codes', compact('codes'));
    }

    public function createPromoCode(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:20|unique:vouchers,code|regex:/^[A-Za-z0-9]+$/',
            'type'             => 'required|in:fixed,percentage',
            'value'            => 'required|numeric|min:1|max:' . ($request->type === 'percentage' ? 100 : 100000),
            'max_uses'         => 'required|integer|min:1|max:10000',
            'expires_at'       => 'nullable|date|after:today',
            'terms_conditions' => 'nullable|string|max:1000',
        ]);

        Voucher::create([
            'user_id'          => null,
            'code'             => strtoupper($request->code),
            'type'             => $request->type,
            'value'            => $request->value,
            'terms_conditions' => !empty($request->terms_conditions) ? $request->terms_conditions : 'Valid for one-time use per customer on eligible bookings. Cannot be combined with other promotional vouchers.',
            'source'           => 'promo_code',
            'status'           => 'available',
            'max_uses'         => $request->max_uses,
            'expires_at'       => $request->expires_at ? \Carbon\Carbon::parse($request->expires_at) : null,
        ]);

        return back()->with('success', "Promo code '{$request->code}' created successfully.");
    }

    public function analytics()
    {
        $totalMembers = Membership::count();
        $tierBreakdown = Membership::selectRaw('tier, count(*) as count')->groupBy('tier')->pluck('count', 'tier')->toArray();

        $totalPointsIssued = \App\Models\PointTransaction::whereIn('type', ['earn', 'bonus'])->sum('points');
        $totalPointsRedeemed = \App\Models\PointTransaction::where('type', 'redeem')->sum('points');

        $topPromoCodes = Voucher::whereNotNull('max_uses')->orderByDesc('current_uses')->take(5)->get();

        $monthlyPoints = \App\Models\PointTransaction::whereIn('type', ['earn', 'bonus'])
            ->where('created_at', '>=', now()->subMonths(6))
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn ($t) => $t->created_at->format('Y-m'))
            ->map(fn ($group) => (object) [
                'month' => $group->first()->created_at->format('Y-m'),
                'total' => $group->sum('points'),
            ])
            ->values();

        return view('admin.memberships.analytics', compact(
            'totalMembers', 'tierBreakdown', 'totalPointsIssued',
            'totalPointsRedeemed', 'topPromoCodes', 'monthlyPoints'
        ));
    }
}
