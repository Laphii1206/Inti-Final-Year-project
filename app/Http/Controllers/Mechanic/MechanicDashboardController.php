<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class MechanicDashboardController extends Controller
{
    public function index()
    {
        $jobs = Booking::where('assigned_staff_id', auth()->id())
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->whereDate('booking_date', today())
            ->with(['user', 'car', 'service'])
            ->orderBy('start_time')
            ->get();

        return view('mechanic.jobs.index', compact('jobs'));
    }

    public function jobs()
    {
        return $this->index();
    }

    public function complete(Request $request, Booking $booking)
    {
        abort_if($booking->assigned_staff_id !== auth()->id(), 403);
        abort_if($booking->status !== 'in_progress', 422, 'Booking is not in progress.');

        if (!$booking->isPaid()) {
            return redirect()->route('mechanic.jobs.index')->with('error', 'Cannot complete job: Payment Status must be Paid first.');
        }

        $request->validate(['recorded_mileage' => 'required|numeric|min:0']);

        $booking->update([
            'status'           => 'completed',
            'recorded_mileage' => $request->recorded_mileage,
        ]);

        if ($booking->car) {
            $booking->car->update(['mileage' => $request->recorded_mileage]);
        }

        ActivityLogger::log(
            $booking,
            'booking',
            'completed',
            'Mechanic ' . auth()->user()->name . ' marked job as completed',
            auth()->user()
        );

        return redirect()->route('mechanic.jobs.index')->with('success', __('messages.msg_job_completed'));
    }
}