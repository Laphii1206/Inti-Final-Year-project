<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function scan(Request $request)
    {
        return view('mechanic.checkin');
    }

    public function process(Request $request)
    {
        $request->validate(['uuid' => 'required|string|uuid']);

        $booking = Booking::where('uuid', $request->uuid)
            ->with(['user', 'service', 'car'])
            ->first();

        // check QR 码
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code. Booking not found.',
            ], 404);
        }

        // 原来只拦截 completed 和 cancelled，
        // 修复后加入 rejected 和 no_show，符合工作流规范
        $blockedStatuses = [
            Booking::STATUS_COMPLETED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_REJECTED,
            Booking::STATUS_NO_SHOW,
        ];

        if (in_array($booking->status, $blockedStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or Expired Booking. This booking is already ' . $booking->status . '.',
            ], 400);
        }

        // 必须是 confirmed 状态才能扫码签到 
        // 这会跳过管理员审批流程。现在严格要求 confirmed 才行
        if ($booking->status !== Booking::STATUS_CONFIRMED) {
            return response()->json([
                'success' => false,
                'message' => 'Booking is not confirmed yet. Current status: ' . $booking->status . '.',
            ], 400);
        }

        // 日期必须是今天
        if (!$booking->isToday()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or Expired Booking. This booking is not scheduled for today.',
            ], 400);
        }

        // 确保付款状态必须是 Paid 才能进入 In Progress
        if (!$booking->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment Required: Payment Status must be Paid before service can start.',
            ], 400);
        }

        $booking->update([
            'status'            => Booking::STATUS_IN_PROGRESS,
            'qr_scanned_at'     => now(),
            'assigned_staff_id' => $request->user()->id,
        ]);

        ActivityLogger::log(
            $booking,
            'booking',
            'checked_in',
            'Mechanic ' . $request->user()->name . ' scanned check-in for booking #' . $booking->number,
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'booking' => [
                'id'           => $booking->id,
                'number'       => $booking->number,
                'customer'     => $booking->user->name,
                'booking_date' => $booking->booking_date->format('d M Y'),
                'start_time'   => $booking->start_time->format('H:i'),
                'services'     => [$booking->service->name ?? 'N/A'],
                'vehicle'      => $booking->car
                    ? "{$booking->car->brand} {$booking->car->model} ({$booking->car->car_plate})"
                    : 'N/A',
                'status'       => $booking->status,
            ],
        ]);
    }
}
