<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\DB;

#[Signature('app:cleanup-unpaid-bookings')]
#[Description('Cancel online bookings that remain unpaid after 15 minutes to release slot capacity')]
class CleanupUnpaidBookings extends Command
{
    public function handle()
    {
        $threshold = now()->subMinutes(15);

        $bookings = Booking::where('payment_method', 'card')
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->where('created_at', '<', $threshold)
            ->whereDoesntHave('payments', function ($q) {
                $q->where('status', Payment::STATUS_PAID);
            })
            ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            DB::transaction(function () use ($booking, &$count) {
                $b = Booking::where('id', $booking->id)->lockForUpdate()->first();
                if (!$b || !in_array($b->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])) {
                    return;
                }

                $b->update(['status' => Booking::STATUS_CANCELLED]);
                $b->restoreVoucher();

                ActivityLogger::booking(
                    $b,
                    'status_changed',
                    "System auto-cancelled unpaid card booking (exceeded 15-minute payment window)"
                );
                $count++;
            });
        }

        $this->info("Cancelled {$count} unpaid bookings.");
    }
}
