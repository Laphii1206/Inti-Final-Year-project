<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Car;
use App\Notifications\MaintenanceReminder;
use Carbon\Carbon;

#[Signature('app:send-maintenance-reminders')]
#[Description('Send maintenance reminders to users whose cars have not been serviced in over 6 months')]
class SendMaintenanceReminders extends Command
{
    public function handle()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        // Find cars whose last booking was completed over 6 months ago
        $cars = Car::whereHas('bookings', function ($query) use ($sixMonthsAgo) {
            $query->where('status', \App\Models\Booking::STATUS_COMPLETED)
                  ->where('booking_date', '<=', $sixMonthsAgo);
        })->whereDoesntHave('bookings', function ($query) use ($sixMonthsAgo) {
            $query->where('status', \App\Models\Booking::STATUS_COMPLETED)
                  ->where('booking_date', '>', $sixMonthsAgo);
        })->get();

        $count = 0;
        foreach ($cars as $car) {
            if ($car->user) {
                $car->user->notify(new MaintenanceReminder($car, 'It has been 6 months since your last service.'));
                $count++;
                
                // Add a 1-second delay to prevent hitting Mailtrap's testing tier rate limits (Too many emails per second)
                sleep(1);
            }
        }

        $this->info("Sent {$count} maintenance reminders.");
    }
}
