<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogBookingStatusChange implements ShouldQueue
{
    use InteractsWithQueue;

    /*Create the event listener*/
    public function __construct()
    {
        //
    }

    public function handle(BookingStatusChanged $event): void
    {
        $booking = $event->booking;
        $oldStatus = $event->oldStatus;
        $newStatus = $booking->status;

        if ($oldStatus !== $newStatus) {
            \App\Services\ActivityLogger::booking(
                $booking,
                'status_changed',
                "Booking status changed from {$oldStatus} to {$newStatus}"
            );
        }
    }
}
