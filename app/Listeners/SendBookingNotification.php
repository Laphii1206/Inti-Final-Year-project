<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /*Create the event listener.*/
    public function __construct()
    {
        //
    }

    public function handle(BookingStatusChanged $event): void
    {
        $booking = $event->booking;
        $status = $booking->status;

        try {
            if ($status === \App\Models\Booking::STATUS_CONFIRMED) {
                $booking->user->notify(new \App\Notifications\BookingConfirmed($booking));
            } elseif (in_array($status, [\App\Models\Booking::STATUS_CANCELLED, \App\Models\Booking::STATUS_REJECTED])) {
                $booking->user->notify(new \App\Notifications\BookingCancelled($booking));
            } elseif ($status !== $event->oldStatus) {
                $booking->user->notify(new \App\Notifications\BookingStatusUpdate($booking));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Booking notification failed for booking #' . $booking->id . ': ' . $e->getMessage());
        }
    }
}
