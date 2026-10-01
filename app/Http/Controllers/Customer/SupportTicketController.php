<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\SubmitTicketRequest;
use App\Models\Booking;
use App\Models\SupportTicket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use App\Models\User;
use App\Notifications\SupportTicketReplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    public function store(SubmitTicketRequest $request)
    {
        if ($request->filled('booking_id')) {
            Booking::where('id', $request->booking_id)
                   ->where('user_id', auth()->id())
                   ->firstOrFail();
        }

        $ticket = SupportTicket::create([
            'user_id'     => auth()->id(),
            'booking_id'  => $request->booking_id,
            'type'        => $request->type,
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => SupportTicket::STATUS_OPEN,
            'priority'    => $request->priority ?? SupportTicket::PRIORITY_MEDIUM,
        ]);

        $message = TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'sender_id'   => auth()->id(),
            'sender_role' => 'customer',
            'body'        => $request->description,
        ]);

        if ($request->hasFile('attachments')) {
            $this->storeAttachments($request->file('attachments'), $ticket, $message);
        }

        $adminsToNotify = $ticket->assignedAdmin ? collect([$ticket->assignedAdmin]) : User::where('role', User::ROLE_ADMIN)->get();
        \Illuminate\Support\Facades\Notification::send($adminsToNotify, new SupportTicketReplied($ticket));

        return redirect()
            ->route('support-tickets.show', $ticket->ticket_number)
            ->with('success', __('messages.msg_ticket_submitted'));
    }

    public function show(string $ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
            ->where('user_id', auth()->id())
            ->with(['messages.sender', 'messages.attachments', 'booking.service', 'booking.branch'])
            ->firstOrFail();

        $messages = $ticket->publicMessages()->with(['sender', 'attachments'])->get();

        return view('customer.support-tickets.show', compact('ticket', 'messages'));
    }

    public function sendMessage(SendMessageRequest $request, string $ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->is_locked) {
            return back()->with('error', __('messages.msg_ticket_locked'));
        }

        if (!$ticket->canReceiveMessages()) {
            return back()->with('error', __('messages.msg_ticket_cannot_receive', ['status' => $ticket->status]));
        }

        $message = TicketMessage::create([
            'ticket_id'   => $ticket->id,
            'sender_id'   => auth()->id(),
            'sender_role' => 'customer',
            'body'        => $request->body,
        ]);

        if ($ticket->status === SupportTicket::STATUS_RESOLVED) {
            $ticket->update(['status' => SupportTicket::STATUS_OPEN]);
        }

        if ($request->hasFile('attachments')) {
            $this->storeAttachments($request->file('attachments'), $ticket, $message);
        }

        $adminsToNotify = $ticket->assignedAdmin ? collect([$ticket->assignedAdmin]) : User::where('role', User::ROLE_ADMIN)->get();
        \Illuminate\Support\Facades\Notification::send($adminsToNotify, new SupportTicketReplied($ticket));

        return redirect()
            ->route('support-tickets.show', $ticket->ticket_number)
            ->with('success', __('messages.msg_ticket_msg_sent'));
    }

    public function markAsResolved(string $ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status === SupportTicket::STATUS_RESOLVED || $ticket->status === SupportTicket::STATUS_CLOSED) {
            return back()->with('error', __('messages.msg_ticket_already_closed'));
        }

        $ticket->update(['status' => SupportTicket::STATUS_RESOLVED]);

        return back()->with('success', __('messages.msg_ticket_resolved'));
    }

    public function close(string $ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status !== SupportTicket::STATUS_RESOLVED) {
            return back()->with('error', __('messages.msg_ticket_only_resolved'));
        }

        $ticket->update(['status' => SupportTicket::STATUS_CLOSED]);

        return back()->with('success', __('messages.msg_ticket_closed'));
    }

    public function rate(Request $request, string $ticketNumber)
    {
        $ticket = SupportTicket::where('ticket_number', $ticketNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($ticket->status, [SupportTicket::STATUS_RESOLVED, SupportTicket::STATUS_CLOSED])) {
            return back()->with('error', __('messages.msg_ticket_rate_not_allowed'));
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $ticket->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', __('messages.msg_ticket_feedback'));
    }

    private function storeAttachments(array $files, SupportTicket $ticket, TicketMessage $message): void
    {
        foreach ($files as $file) {
            $directory    = "support-attachments/{$ticket->id}";
            $storedName   = $file->hashName();
            $path         = $file->storeAs($directory, $storedName, 'public');

            TicketAttachment::create([
                'ticket_id'         => $ticket->id,
                'message_id'        => $message->id,
                'sender_id'         => auth()->id(),
                'original_filename' => $file->getClientOriginalName(),
                'stored_filename'   => $storedName,
                'file_path'         => $path,
                'mime_type'         => $file->getMimeType(),
                'file_size'         => $file->getSize(),
            ]);
        }
    }
}
