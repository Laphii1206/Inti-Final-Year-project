<?php

namespace App\Listeners;

use App\Events\BookingStatusChanged;
use App\Models\Booking;
use App\Services\MembershipService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardBookingPoints implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(protected MembershipService $membershipService) {}

    public function handle(BookingStatusChanged $event): void
    {
        $booking   = $event->booking;
        $oldStatus = $event->oldStatus;

        // Only award when the booking has JUST transitioned into completed.
        if ($oldStatus === Booking::STATUS_COMPLETED) {
            return;
        }
        if ($booking->status !== Booking::STATUS_COMPLETED) {
            return;
        }

        // Guard against double-awarding: skip if points were already recorded for this booking.
        $alreadyAwarded = \App\Models\PointTransaction::where('reference_type', 'booking')
            ->where('reference_id', $booking->id)
            ->exists();
        if ($alreadyAwarded) {
            return;
        }

        $this->membershipService->awardPointsForBooking($booking);
    }
}
