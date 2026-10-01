<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Str;

class MembershipService
{
    public function createForNewUser(User $user): Membership
    {
        do {
            $num = '';
            for ($i = 0; $i < 16; $i++) {
                $num .= random_int(0, 9);
            }
            $membershipId = substr($num, 0, 4) . ' ' . substr($num, 4, 4) . ' ' . substr($num, 8, 4) . ' ' . substr($num, 12, 4);
        } while (Membership::where('membership_id', $membershipId)->exists());

        $pool = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
        do {
            $referralCode = substr(str_shuffle($pool), 0, 8);
        } while (Membership::where('referral_code', $referralCode)->exists());

        return Membership::create([
            'user_id'              => $user->id,
            'membership_id'        => $membershipId,
            'tier'                 => Membership::TIER_BRONZE,
            'reward_points'        => 0,
            'cumulative_annual_spending' => 0,
            'membership_join_date' => now()->toDateString(),
            'referral_code'        => $referralCode,
        ]);
    }

    public function awardPointsForBooking(Booking $booking): void
    {
        $user = $booking->user;
        if (!$user || !$user->isCustomer()) return;

        $membership = $user->membership;
        if (!$membership) {
            $membership = $this->createForNewUser($user);
        }

        $spending = (float) $booking->service_price_at_booking;
        $points   = (int) floor($spending * $membership->getMultiplier());

        $description = "Points earned for booking #{$booking->number}";

        if ($user->date_of_birth && $user->date_of_birth->month === now()->month) {
            $points *= 2;
            $description = "🎂 Birthday Month Double Points for booking #{$booking->number}!";
        }

        $membership->increment('cumulative_annual_spending', $spending);
        $membership->increment('reward_points', $points);
        $membership->touch();

        PointTransaction::create([
            'user_id'        => $user->id,
            'type'           => 'earn',
            'points'         => $points,
            'description'    => $description,
            'reference_type' => 'booking',
            'reference_id'   => $booking->id,
        ]);

        $this->evaluateTierUpgrade($user);

        $completedBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $rewardService = app(\App\Services\RewardService::class);
        $rewardService->checkAndAwardMissions($user, 'first_booking', $completedBookingsCount);
        $rewardService->checkAndAwardMissions($user, 'spending_milestone', $spending);

        $monthlyBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereYear('booking_date', now()->year)
            ->whereMonth('booking_date', now()->month)
            ->count();
        $rewardService->checkAndAwardMissions($user, 'booking_count_monthly', $monthlyBookingsCount);

        if ($completedBookingsCount === 1 && $user->referred_by) {
            $referrer = User::find($user->referred_by);
            if ($referrer) {
                $awarded = app(\App\Services\RewardService::class)->checkAndAwardMissions($referrer, 'referral', 1);

                $referralPoints = 200;
                if (count($awarded) > 0) {
                    $referralPoints = $awarded[0]->reward_points;
                }

                $referrer->notify(new \App\Notifications\ReferralSuccessNotification($user->name, $referralPoints));

                $this->addPoints(
                    $user,
                    50,
                    "Welcome bonus for using a referral code!",
                    'earn'
                );
            }
        }
    }

    public function evaluateTierUpgrade(User $user): bool
    {
        $membership = $user->membership;
        if (!$membership) return false;

        $oldTier = $membership->tier;
        $spending = $membership->cumulative_annual_spending;

        $newTier = $oldTier;
        if ($spending >= Membership::GOLD_THRESHOLD) {
            $newTier = Membership::TIER_GOLD;
        } elseif ($spending >= Membership::SILVER_THRESHOLD) {
            $newTier = Membership::TIER_SILVER;
        }

        if ($newTier !== $oldTier) {
            $membership->update([
                'tier'                  => $newTier,
                'last_tier_evaluated_at' => now(),
            ]);

            $user->notify(new \App\Notifications\MembershipTierUpgraded($newTier));

            session()->flash('confetti', true);

            return true;
        }

        $membership->update(['last_tier_evaluated_at' => now()]);
        return false;
    }

    public function resetAnnualSpending(User $user): void
    {
        $membership = $user->membership;
        if (!$membership) return;

        $membership->update([
            'cumulative_annual_spending' => 0,
        ]);

        $newTier = Membership::TIER_BRONZE;

        $membership->update([
            'tier'                  => $newTier,
            'last_tier_evaluated_at' => now(),
        ]);
    }

    public function addPoints(User $user, int $points, string $description, string $type = 'admin_adjust'): void
    {
        $membership = $user->membership ?? $this->createForNewUser($user);
        $membership->increment('reward_points', $points);

        PointTransaction::create([
            'user_id'     => $user->id,
            'type'        => $type,
            'points'      => $points,
            'description' => $description,
        ]);
    }

    public function deductPoints(User $user, int $points, string $description, string $type = 'redeem'): bool
    {
        $membership = $user->membership;
        if (!$membership || $membership->reward_points < $points) {
            return false;
        }

        $membership->decrement('reward_points', $points);

        PointTransaction::create([
            'user_id'     => $user->id,
            'type'        => $type,
            'points'      => -$points,
            'description' => $description,
        ]);

        return true;
    }
}
