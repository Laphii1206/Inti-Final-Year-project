<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['user', 'assignedStaff', 'branch', 'service', 'car'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date, fn($q) => $q->whereDate('booking_date', $request->date))
            ->when($request->search, fn($q) => $q->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            }))
            ->latest('booking_date')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user', 'assignedStaff', 'branch', 'car', 'service',
            'review', 'activityLogs.user',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status'            => 'sometimes|in:pending,confirmed,in_progress,completed,cancelled,rejected,no_show',
            'booking_date'      => 'sometimes|date',
            'start_time'        => 'sometimes|date_format:H:i',
            'assigned_staff_id' => [
                'nullable',
                Rule::exists('users', 'id')->where('role', User::ROLE_MECHANIC),
            ],
            'branch_id'         => 'nullable|exists:branches,id',
            'customer_remark'   => 'nullable|string',
        ]);

        if (isset($validated['status']) && $validated['status'] !== $booking->status) {
            if (in_array($validated['status'], [Booking::STATUS_IN_PROGRESS, Booking::STATUS_COMPLETED])) {
                if (!$booking->isPaid()) {
                    $statusLabel = $validated['status'] === Booking::STATUS_IN_PROGRESS ? 'In Progress' : 'Completed';
                    return back()->with('error', "Cannot change status to {$statusLabel}: Payment Status must be Paid first.");
                }
            }

            if ($validated['status'] === Booking::STATUS_NO_SHOW) {
                $bookingDateTime = \Carbon\Carbon::parse(
                    $booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time->format('H:i:s')
                );
                if (now()->lessThan($bookingDateTime)) {
                    return back()->with('error', 'Cannot mark as no-show before the booking time has passed.');
                }
            }

            if (in_array($validated['status'], [Booking::STATUS_CANCELLED, Booking::STATUS_REJECTED])) {
                if (empty($booking->cancellation_reason)) {
                    $validated['cancellation_reason'] = 'Status updated to ' . $validated['status'] . ' by Admin (' . auth()->user()->name . ')';
                }
            }
        }

        DB::transaction(function () use ($booking, $validated) {
            $oldStatus = $booking->status;
            $booking->update($validated);

            if (isset($validated['status']) && $oldStatus !== $validated['status']) {
                if (in_array($validated['status'], [Booking::STATUS_CANCELLED, Booking::STATUS_REJECTED])) {
                    $booking->restoreVoucher();
                    $booking->processRefund();
                }
            }
        });

        ActivityLogger::admin(
            $booking,
            'booking_updated',
            auth()->user()->name . ' updated booking #' . $booking->number
        );

        return back()->with('success', 'Booking updated successfully.');
    }

    /*检查状态必须是 pending 或 confirmed 才能取消*/
    public function cancel(Request $request, Booking $booking)
    {
        // 检查状态是否允许取消
        if (!in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])) {
            return back()->with('error', 'Only pending or confirmed bookings can be cancelled.');
        }

        // 必须填写取消原因
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status'              => Booking::STATUS_CANCELLED,
                'cancellation_reason' => $request->cancellation_reason,
            ]);
            $booking->restoreVoucher();
            $booking->processRefund();

            ActivityLogger::admin(
                $booking,
                'booking_cancelled',
                auth()->user()->name . ' cancelled booking #' . $booking->number . '. Reason: ' . $request->cancellation_reason
            );
        });

        return back()->with('success', 'Booking cancelled successfully.');
    }

    /*- 状态必须是 pending 或 confirmed only can reject*/
    public function reject(Request $request, Booking $booking)
    {
        if (!in_array($booking->status, [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])) {
            return back()->with('error', 'Only pending or confirmed bookings can be rejected.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status'              => Booking::STATUS_REJECTED,
                'cancellation_reason' => $request->cancellation_reason,
            ]);
            $booking->restoreVoucher();
            $booking->processRefund();

            ActivityLogger::admin(
                $booking,
                'booking_rejected',
                auth()->user()->name . ' rejected booking #' . $booking->number . '. Reason: ' . $request->cancellation_reason
            );
        });

        return back()->with('success', 'Booking rejected successfully.');
    }

    /*Admin 标记为 No Show,状态必须是 confirmed,预订时间必须已过（当天时间已超过预订的 start_time）*/
    public function markNoShow(Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            return back()->with('error', 'Only confirmed bookings can be marked as no-show.');
        }

        // 检查预订时间是否已过
        $bookingDateTime = \Carbon\Carbon::parse(
            $booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time->format('H:i:s')
        );

        if (now()->lessThan($bookingDateTime)) {
            return back()->with('error', 'Cannot mark as no-show before the booking time has passed.');
        }

        $booking->update(['status' => Booking::STATUS_NO_SHOW]);

        ActivityLogger::admin(
            $booking,
            'booking_no_show',
            auth()->user()->name . ' marked booking #' . $booking->number . ' as No Show.'
        );

        return back()->with('success', 'Booking marked as No Show.');
    }

    public function complete(Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_IN_PROGRESS) {
            return back()->with('error', 'Only in-progress bookings can be marked as completed.');
        }

        if (!$booking->isPaid()) {
            return back()->with('error', 'Cannot complete booking: Payment Status must be Paid first.');
        }

        $booking->update(['status' => Booking::STATUS_COMPLETED]);

        ActivityLogger::admin(
            $booking,
            'booking_completed',
            auth()->user()->name . ' marked booking #' . $booking->number . ' as Completed.'
        );

        return back()->with('success', 'Booking completed successfully.');
    }

    public function markPaid(Booking $booking)
    {
        if ($booking->isPaid()) {
            return back()->with('error', __('admin.bk_already_paid'));
        }

        $payment = $booking->payments()
            ->where('status', \App\Models\Payment::STATUS_PENDING)
            ->latest()
            ->first();

        if ($payment) {
            $payment->update([
                'status'  => \App\Models\Payment::STATUS_PAID,
                'paid_at' => now(),
            ]);
        } else {
            $amount = max(0, (float) $booking->service_price_at_booking - (float) ($booking->discount_amount ?? 0));
            \App\Models\Payment::create([
                'booking_id' => $booking->id,
                'user_id'    => $booking->user_id,
                'gateway'    => 'counter',
                'amount'     => $amount,
                'currency'   => 'MYR',
                'status'     => \App\Models\Payment::STATUS_PAID,
                'paid_at'    => now(),
            ]);
        }

        if ($booking->voucher_id) {
            $voucher = \App\Models\Voucher::find($booking->voucher_id);
            if ($voucher && $voucher->status === 'available') {
                $voucher->update([
                    'status'             => 'used',
                    'used_at'            => now(),
                    'used_in_booking_id' => $booking->id,
                ]);
            }
        }

        return back()->with('success', __('admin.bk_marked_paid'));
    }

    public function generateQr(Booking $booking)
    {
        $qrService = new \App\Services\QrCodeService();
        $qrService->generate($booking);
        return back()->with('success', 'QR Code generated successfully.');
    }
}
