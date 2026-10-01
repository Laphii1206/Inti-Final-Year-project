<?php

namespace Database\Seeders;

use App\Models\Mission;
use Illuminate\Database\Seeder;

class MissionSeeder extends Seeder
{
    public function run(): void
    {
        $missions = [
            [
                'code'          => 'first_booking',
                'name'          => 'Complete First Booking',
                'description'   => 'Complete your very first service booking with us.',
                'reward_points' => 50,
                'icon'          => 'fa-calendar-check',
                'trigger_type'  => 'first_booking',
                'trigger_value' => 1,
                'is_repeatable' => false,
            ],
            [
                'code'          => 'spend_100',
                'name'          => 'Spend RM100',
                'description'   => 'Spend RM100 in a single booking.',
                'reward_points' => 100,
                'icon'          => 'fa-money-bill-wave',
                'trigger_type'  => 'spending_milestone',
                'trigger_value' => 100.00,
                'is_repeatable' => false,
            ],
            [
                'code'          => 'three_bookings_month',
                'name'          => 'Make 3 Bookings in a Month',
                'description'   => 'Complete 3 service bookings within the same calendar month.',
                'reward_points' => 150,
                'icon'          => 'fa-list-check',
                'trigger_type'  => 'booking_count_monthly',
                'trigger_value' => 3,
                'is_repeatable' => false,
            ],
            [
                'code'          => 'refer_friend',
                'name'          => 'Refer a Friend',
                'description'   => 'Successfully refer a friend who registers and completes their first booking.',
                'reward_points' => 200,
                'icon'          => 'fa-user-plus',
                'trigger_type'  => 'referral',
                'trigger_value' => 1,
                'is_repeatable' => true,
            ],
            [
                'code'          => 'checkin_7_days',
                'name'          => 'Check-in 7 Consecutive Days',
                'description'   => 'Check in for 7 days in a row without missing a day.',
                'reward_points' => 50,
                'icon'          => 'fa-fire',
                'trigger_type'  => 'checkin_streak',
                'trigger_value' => 7,
                'is_repeatable' => true,
            ],
        ];

        foreach ($missions as $mission) {
            Mission::updateOrCreate(['code' => $mission['code']], $mission);
        }

        $this->command->info('Missions seeded successfully.');
    }
}
