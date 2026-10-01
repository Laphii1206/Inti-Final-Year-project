<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:mark-no-show-bookings')]
#[Description('Mark confirmed or pending bookings as no-show if their service end time has passed')]
class MarkNoShowBookings extends Command
{
    /*Execute the console command.*/
    public function handle()
    {
        $now = now();
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');

        // Bookings before today, or today but service end time has passed
        $bookings = \App\Models\Booking::whereIn('status', [
                \App\Models\Booking::STATUS_CONFIRMED,
                \App\Models\Booking::STATUS_PENDING,
            ])
            ->where(function ($query) use ($date, $time) {
                $query->whereDate('booking_date', '<', $date)
                      ->orWhere(function ($q) use ($date, $time) {
                          $q->whereDate('booking_date', '=', $date)
                            ->where('end_time', '<', $time);
                      });
            })
            ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            $booking->update(['status' => \App\Models\Booking::STATUS_NO_SHOW]);
            \App\Services\ActivityLogger::booking(
                $booking,
                'status_changed',
                "System auto-marked booking as No Show (time elapsed)"
            );
            $count++;
        }

        $this->info("Marked {$count} bookings as no-show.");
    }
}
