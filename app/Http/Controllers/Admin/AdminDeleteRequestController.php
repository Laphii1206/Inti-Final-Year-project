<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DeleteAccountRequest;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class AdminDeleteRequestController extends Controller
{
    public function index()
    {
        $requests = DeleteAccountRequest::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.delete-requests.index', compact('requests'));
    }

    public function approve(DeleteAccountRequest $deleteRequest)
    {
        $user = $deleteRequest->user;

        if ($user->is_main_admin || $user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete Main Admin or your own account.');
        }

        $activeBookings = $user->bookings()
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->count();

        if ($activeBookings > 0) {
            return back()->with(
                'error',
                "Cannot delete account. {$user->name} still has {$activeBookings} active booking(s). Please cancel them first."
            );
        }

        $deleteRequest->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $user->notify(new \App\Notifications\AccountDeletionApproved());

        $user->delete(); // soft delete

        ActivityLogger::admin(
            $user,
            'account_delete_approved',
            auth()->user()->name . ' approved delete request for ' . $user->name
        );

        return back()->with('success', 'Account delete request approved. User account removed.');
    }

    public function reject(Request $request, DeleteAccountRequest $deleteRequest)
    {
        $request->validate(['admin_notes' => 'nullable|string|max:500']);

        $deleteRequest->update([
            'status'      => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        ActivityLogger::admin(
            $deleteRequest->user,
            'account_delete_rejected',
            auth()->user()->name . ' rejected delete request for ' . $deleteRequest->user->name
        );

        return back()->with('success', 'Request rejected.');
    }
}
