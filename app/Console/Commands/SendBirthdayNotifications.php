<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Membership;
use App\Models\User;
use App\Models\Voucher;
use App\Notifications\BirthdayNotification;
use App\Services\MembershipService;
use Carbon\Carbon;
use Illuminate\Support\Str;

#[Signature('app:send-birthday-notifications')]
#[Description('Send tier-based birthday notifications, bonus points and exclusive reward vouchers to users on their birthday')]
class SendBirthdayNotifications extends Command
{
    public function handle(MembershipService $membershipService)
    {
        $today = Carbon::now();
        $this->info("Checking tier-based birthday notifications for date: {$today->format('Y-m-d')}...");

        // Find all users who have a date_of_birth set matching today
        $users = User::whereNotNull('date_of_birth')->with('membership')->get()->filter(function ($user) use ($today) {
            return $user->date_of_birth &&
                   $user->date_of_birth->month === $today->month &&
                   $user->date_of_birth->day === $today->day;
        });

        $count = 0;

        foreach ($users as $user) {
            // Check if user already received a birthday voucher or notification this year
            $alreadyReceivedVoucher = Voucher::where('user_id', $user->id)
                ->where('source', 'birthday')
                ->whereYear('created_at', $today->year)
                ->exists();

            $alreadyNotified = $user->notifications()
                ->where('type', BirthdayNotification::class)
                ->whereYear('created_at', $today->year)
                ->exists();

            if ($alreadyReceivedVoucher || $alreadyNotified) {
                $this->info("User {$user->id} ({$user->name}) has already received birthday rewards for {$today->year}. Skipping.");
                continue;
            }

            $tier = $user->membership?->tier ?? Membership::TIER_BRONZE;

            if ($tier === Membership::TIER_BRONZE) {
                // Bronze tier: Enjoy 2X double points on bookings during birthday month, no free fixed vouchers
                $user->notify(new BirthdayNotification([], 0, 'bronze'));
                $this->info("User {$user->id} ({$user->name}) [Bronze]: Sent 2X Birthday Month double points notification.");
            } elseif ($tier === Membership::TIER_SILVER) {
                // Silver tier: 100 Bonus Points + 1 RM 50.00 Birthday Voucher + 2X double points all month
                $membershipService->addPoints(
                    $user,
                    100,
                    '🎂 Birthday Bonus Gift Points!',
                    'bonus'
                );

                $voucher = Voucher::create([
                    'user_id'          => $user->id,
                    'code'             => 'BDAY-' . $today->format('Y') . '-' . strtoupper(Str::random(5)),
                    'type'             => 'fixed',
                    'value'            => 50.00,
                    'terms_conditions' => __('rewards.tnc_birthday'),
                    'source'           => 'birthday',
                    'status'           => 'available',
                    'expires_at'       => $today->copy()->endOfMonth(),
                ]);

                $user->notify(new BirthdayNotification([$voucher], 100, 'silver'));
                $this->info("User {$user->id} ({$user->name}) [Silver]: Sent 1 RM 50 Birthday Voucher and 100 Bonus Points.");
            } elseif ($tier === Membership::TIER_GOLD) {
                // Gold tier: 200 Bonus Points + 3 RM 50.00 Birthday Vouchers + 2X double points all month
                $membershipService->addPoints(
                    $user,
                    200,
                    '🎂 Gold VIP Birthday Bonus Points!',
                    'bonus'
                );

                $vouchers = [];
                for ($i = 0; $i < 3; $i++) {
                    $vouchers[] = Voucher::create([
                        'user_id'          => $user->id,
                        'code'             => 'BDAY-' . $today->format('Y') . '-' . strtoupper(Str::random(5)),
                        'type'             => 'fixed',
                        'value'            => 50.00,
                        'terms_conditions' => __('rewards.tnc_birthday'),
                        'source'           => 'birthday',
                        'status'           => 'available',
                        'expires_at'       => $today->copy()->endOfMonth(),
                    ]);
                }

                $user->notify(new BirthdayNotification($vouchers, 200, 'gold'));
                $this->info("User {$user->id} ({$user->name}) [Gold]: Sent 3 RM 50 Birthday Vouchers and 200 Bonus Points.");
            }

            $count++;
            sleep(1);
        }

        $this->info("Successfully processed {$count} birthday notifications and rewards.");
        return 0;
    }
}
