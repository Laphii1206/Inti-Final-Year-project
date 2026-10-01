<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReleaseBookingCapacity implements ShouldQueue
{
    use InteractsWithQueue;

    /*Create the event listener.*/
    public function __construct()
    {
        //
    }

    /*Handle the event.*/
    public function handle(BookingStatusChanged $event): void
    {
        $booking = $event->booking;
        if (in_array($booking->status, [\App\Models\Booking::STATUS_CANCELLED, \App\Models\Booking::STATUS_REJECTED])) {
            \App\Services\ActivityLogger::booking(
                $booking,
                'capacity_released',
                "Capacity released for slot {$booking->booking_date->format('Y-m-d')} at {$booking->start_time->format('H:i')}"
            );
        }
    }
}
