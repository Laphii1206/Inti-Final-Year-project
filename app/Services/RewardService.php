<?php

namespace App\Services;

use App\Models\DailyCheckin;
use App\Models\Membership;
use App\Models\Mission;
use App\Models\PointTransaction;
use App\Models\SpinResult;
use App\Models\User;
use App\Models\UserMission;
use App\Models\Voucher;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RewardService
{
    public function __construct(protected MembershipService $membershipService) {}

    private function getCleanRandom(int $length = 6): string
    {
        $pool   = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // Excludes l, I, i, L, o, O, 0, 1
        $poolLen = strlen($pool);
        $result  = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $pool[random_int(0, $poolLen - 1)];
        }
        return $result;
    }

    public function processCheckin(User $user): array
    {
        if (DailyCheckin::hasCheckedInToday($user->id)) {
            return [
                'success' => false,
                'message' => __('rewards.msg_already_checked_in'),
            ];
        }

        $today     = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $lastCheckin = DailyCheckin::where('user_id', $user->id)
            ->orderByDesc('checked_in_date')
            ->first();

        $streak = 1;
        if ($lastCheckin) {
            $lastDate = is_string($lastCheckin->checked_in_date)
                ? substr($lastCheckin->checked_in_date, 0, 10)
                : $lastCheckin->checked_in_date->toDateString();

            if ($lastDate === $yesterday) {
                $streak = $lastCheckin->streak_count + 1;
            }
        }

        $basePoints     = Membership::CHECKIN_POINTS;
        $streakBonus    = false;
        $bonusPoints    = 0;

        if ($streak % Membership::CHECKIN_STREAK_DAYS === 0) {
            $streakBonus = true;
            $bonusPoints = Membership::CHECKIN_STREAK_BONUS;
        }

        $totalPoints = $basePoints + $bonusPoints;

        DailyCheckin::create([
            'user_id'             => $user->id,
            'checked_in_date'     => $today,
            'streak_count'        => $streak,
            'points_awarded'      => $totalPoints,
            'streak_bonus_awarded' => $streakBonus,
        ]);

        $membership = $user->membership ?? $this->membershipService->createForNewUser($user);
        $membership->increment('reward_points', $totalPoints);

        PointTransaction::create([
            'user_id'     => $user->id,
            'type'        => 'bonus',
            'points'      => $totalPoints,
            'description' => "Daily check-in (Day {$streak}" . ($streakBonus ? ", 7-day streak bonus!" : "") . ")",
            'reference_type' => 'daily_checkin',
        ]);

        $this->checkAndAwardMissions($user, 'checkin_streak', $streak);

        $message = $streakBonus
            ? __('rewards.msg_checkin_success', ['points' => $basePoints]) . __('rewards.msg_checkin_streak_bonus', ['bonus' => $bonusPoints])
            : __('rewards.msg_checkin_success', ['points' => $totalPoints]);

        return [
            'success'      => true,
            'points'       => $totalPoints,
            'streak'       => $streak,
            'streak_bonus' => $streakBonus,
            'bonus_points' => $bonusPoints,
            'message'      => $message,
        ];
    }

    public function checkAndAwardMissions(User $user, string $triggerType, mixed $triggerValue): array
    {
        $awarded = [];

        $missions = Mission::where('trigger_type', $triggerType)
            ->where('is_active', true)
            ->get();

        foreach ($missions as $mission) {
            if (!$mission->is_repeatable && $mission->isCompletedByUser($user->id)) {
                continue;
            }

            $qualified = false;

            switch ($mission->trigger_type) {
                case 'first_booking':
                    $qualified = (int) $triggerValue >= 1;
                    break;

                case 'spending_milestone':
                    $qualified = (float) $triggerValue >= (float) $mission->trigger_value;
                    break;

                case 'referral':
                    $qualified = (int) $triggerValue >= 1;
                    break;

                case 'booking_count_monthly':
                case 'checkin_streak':
                    $val = (int) $triggerValue;
                    $target = (int) $mission->trigger_value;
                    if ($target > 0) {
                        $qualified = $mission->is_repeatable ? ($val > 0 && $val % $target === 0) : ($val >= $target);
                    }
                    break;
            }

            if ($qualified) {
                UserMission::create([
                    'user_id'       => $user->id,
                    'mission_id'    => $mission->id,
                    'points_awarded' => $mission->reward_points,
                    'completed_at'  => now(),
                ]);

                $membership = $user->membership ?? $this->membershipService->createForNewUser($user);
                $membership->increment('reward_points', $mission->reward_points);

                PointTransaction::create([
                    'user_id'        => $user->id,
                    'type'           => 'bonus',
                    'points'         => $mission->reward_points,
                    'description'    => "Mission completed: {$mission->name}",
                    'reference_type' => 'mission',
                    'reference_id'   => $mission->id,
                ]);

                $awarded[] = $mission;
            }
        }

        return $awarded;
    }

    public function redeemPointsForVoucher(User $user, int $pointsCost): array
    {
        $catalogue = [
            200  => ['type' => 'fixed', 'value' => 10.00, 'label' => __('rewards.cat_rm10_cash_voucher'), 'min_tier' => 'bronze'],
            500  => ['type' => 'fixed', 'value' => 25.00, 'label' => __('rewards.cat_rm25_cash_voucher'), 'min_tier' => 'silver'],
            1000 => ['type' => 'fixed', 'value' => 50.00, 'label' => __('rewards.cat_rm50_vip_voucher'), 'min_tier' => 'gold'],
        ];

        if (!isset($catalogue[$pointsCost])) {
            return ['success' => false, 'message' => __('rewards.msg_invalid_reward_item')];
        }

        $membership = $user->membership;
        if (!$membership || $membership->reward_points < $pointsCost) {
            return ['success' => false, 'message' => __('rewards.msg_not_enough_points_redeem')];
        }

        $item = $catalogue[$pointsCost];

        $tierHierarchy = ['bronze' => 1, 'silver' => 2, 'gold' => 3];
        $userTier = $membership->tier ?? 'bronze';
        if ($tierHierarchy[$userTier] < $tierHierarchy[$item['min_tier']]) {
            return ['success' => false, 'message' => __('rewards.msg_tier_req_redeem', ['tier' => ucfirst($item['min_tier'])])];
        }

        $this->membershipService->deductPoints(
            $user,
            $pointsCost,
            "Points redeemed for {$item['label']}",
            'redeem'
        );

        $voucher = Voucher::create([
            'user_id'          => $user->id,
            'code'             => 'VN' . $this->getCleanRandom(6),
            'type'             => $item['type'],
            'value'            => $item['value'],
            'terms_conditions' => __('rewards.tnc_points_redemption'),
            'source'           => 'redeemed_points',
            'status'           => 'available',
            'points_cost'      => $pointsCost,
            'expires_at'       => now()->addDays(90),
        ]);

        return [
            'success' => true,
            'voucher' => $voucher,
            'message' => __('rewards.msg_voucher_redeemed', ['label' => $item['label'], 'code' => $voucher->code, 'points' => $pointsCost]),
        ];
    }

    public function redeemPromoCode(User $user, string $code): array
    {
        $code = strtoupper(trim($code));

        try {
            $result = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $code) {
                // Lock the promo row to prevent concurrent double-redemptions
                $promo = Voucher::where('code', $code)
                    ->whereNull('user_id')
                    ->lockForUpdate()
                    ->first();

                if (!$promo) {
                    return ['success' => false, 'message' => __('rewards.msg_code_invalid')];
                }

                if ($promo->isExpired()) {
                    return ['success' => false, 'message' => __('rewards.vouchers_expired_badge')];
                }

                if ($promo->current_uses >= $promo->max_uses) {
                    return ['success' => false, 'message' => __('rewards.msg_code_invalid')];
                }

                // Check if this user already claimed this promo (inside transaction)
                $alreadyClaimed = Voucher::where('user_id', $user->id)
                    ->where('source', 'promo_code')
                    ->where('code', $code . '-' . $user->id)
                    ->exists();

                if ($alreadyClaimed) {
                    return ['success' => false, 'message' => __('rewards.msg_code_already_claimed')];
                }

                // Atomically increment usage count
                $promo->increment('current_uses');

                $userVoucher = Voucher::create([
                    'user_id'          => $user->id,
                    'code'             => $code . '-' . $user->id,
                    'type'             => $promo->type,
                    'value'            => $promo->value,
                    'terms_conditions' => $promo->getTermsAndConditions(),
                    'source'           => 'promo_code',
                    'status'           => 'available',
                    'expires_at'       => $promo->expires_at,
                ]);

                return [
                    'success' => true,
                    'voucher' => $userVoucher,
                    'message' => __('rewards.msg_code_claimed_success', ['label' => $userVoucher->getValueLabel(), 'code' => $userVoucher->code]),
                ];
            });
        } catch (\Exception $e) {
            return ['success' => false, 'message' => __('rewards.msg_error_occurred') ?? 'An error occurred. Please try again.'];
        }

        return $result;
    }

    public function spinWheel(User $user): array
    {
        $cost = Membership::SPIN_COST;
        $maxSpinsPerDay = Membership::MAX_SPINS_PER_DAY ?? 5;

        $membership = $user->membership;
        if (!$membership || $membership->reward_points < $cost) {
            return ['success' => false, 'message' => __('rewards.msg_not_enough_points_spin')];
        }

        $spinsToday = SpinResult::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($spinsToday >= $maxSpinsPerDay) {
            return ['success' => false, 'message' => __('rewards.msg_daily_spin_limit', ['limit' => $maxSpinsPerDay])];
        }

        $spinNumber = $spinsToday + 1;
        $this->membershipService->deductPoints($user, $cost, "Spin & Win (spin #{$spinNumber} today)", 'spin');

        $prize = $this->rollSpinPrize();

        $voucherId = null;

        if ($prize['type'] === 'voucher') {
            $voucher = Voucher::create([
                'user_id'          => $user->id,
                'code'             => 'SP' . $this->getCleanRandom(6),
                'type'             => $prize['voucher_type'],
                'value'            => $prize['voucher_value'],
                'terms_conditions' => __('rewards.tnc_spin_win'),
                'source'           => 'spin_win',
                'status'           => 'available',
                'expires_at'       => now()->addDays(30),
            ]);
            $voucherId = $voucher->id;
        } elseif ($prize['type'] === 'points') {
            $membership->increment('reward_points', $prize['points']);
            PointTransaction::create([
                'user_id'        => $user->id,
                'type'           => 'bonus',
                'points'         => $prize['points'],
                'description'    => "Spin & Win reward: +{$prize['points']} points",
                'reference_type' => 'spin',
            ]);
        }

        $result = SpinResult::create([
            'user_id'      => $user->id,
            'points_spent' => $cost,
            'reward_type'  => $prize['type'],
            'reward_points' => $prize['points'] ?? 0,
            'voucher_id'   => $voucherId,
            'reward_label' => $prize['label'],
        ]);

        return [
            'success' => true,
            'result'  => $result,
            'prize'   => $prize,
            'message' => $prize['type'] === 'nothing'
                ? __('rewards.msg_spin_better_luck')
                : ($prize['type'] === 'points' ? __('rewards.msg_spin_won_points', ['points' => $prize['points']]) : __('rewards.msg_spin_won_voucher', ['label' => $prize['label'], 'code' => ($voucher->code ?? '')])),
        ];
    }

    private function rollSpinPrize(): array
    {
        $prizes = [
            ['type' => 'points',  'points' => 50,   'label' => '+50 Points',       'weight' => 40],
            ['type' => 'nothing', 'points' => 0,   'label' => 'Better Luck Next Time', 'weight' => 25],
            ['type' => 'points',  'points' => 100,  'label' => '+100 Points',      'weight' => 15],
            ['type' => 'voucher', 'voucher_type' => 'fixed',      'voucher_value' => 5,  'label' => 'RM5 Voucher',       'weight' => 10, 'points' => 0],
            ['type' => 'voucher', 'voucher_type' => 'fixed',      'voucher_value' => 10, 'label' => 'RM10 Voucher',      'weight' => 5, 'points' => 0],
            ['type' => 'voucher', 'voucher_type' => 'percentage', 'voucher_value' => 5,  'label' => '5% Discount Coupon','weight' => 5, 'points' => 0],
        ];

        $totalWeight = array_sum(array_column($prizes, 'weight'));
        $rand = random_int(1, $totalWeight);
        $cumulative = 0;

        foreach ($prizes as $prize) {
            $cumulative += $prize['weight'];
            if ($rand <= $cumulative) {
                return $prize;
            }
        }

        return $prizes[count($prizes) - 1];
    }

    public static function getSpinSegments(): array
    {
        return [
            ['label' => __('rewards.spin_seg_points_50'),          'color' => '#FF2A43', 'match' => '+50 Points'],
            ['label' => __('rewards.spin_seg_rm5_voucher'),        'color' => '#2A2F3D', 'match' => 'RM5 Voucher'],
            ['label' => __('rewards.spin_seg_points_100'),         'color' => '#D80F29', 'match' => '+100 Points'],
            ['label' => __('rewards.spin_seg_try_again'),          'color' => '#1E222D', 'match' => 'Better Luck Next Time'],
            ['label' => __('rewards.spin_seg_rm10_voucher'),       'color' => '#E11D48', 'match' => 'RM10 Voucher'],
            ['label' => __('rewards.spin_seg_10off_discount'),     'color' => '#343A4C', 'match' => '5% Discount Coupon'],
        ];
    }
}
