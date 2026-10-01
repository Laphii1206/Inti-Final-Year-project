<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Models\SupportTicket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\CannedResponse;
use App\Notifications\SupportTicketReplied;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $status     = $request->get('status', 'all');
        $type       = $request->get('type', 'all');
        $priority   = $request->get('priority', 'all');
        $assignedTo = $request->get('assigned_to', 'all');
        $search     = $request->get('search', '');

        $query = SupportTicket::with(['user', 'booking', 'assignedAdmin'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->where('sender_role', 'customer')
                  ->whereRaw("created_at > COALESCE((SELECT MAX(created_at) FROM ticket_messages WHERE ticket_id = support_tickets.id AND sender_role = 'admin'), '1970-01-01 00:00:00')");
            }])
            ->latest();

        if ($status   !== 'all') $query->where('status', $status);
        if ($type     !== 'all') $query->where('type', $type);
        if ($priority !== 'all') $query->where('priority', $priority);
        if ($assignedTo !== 'all') {
            if ($assignedTo === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $assignedTo);
            }
        }
        if ($search)  {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $tickets = $query->paginate(20)->withQueryString();

        $counts = [
            'open'        => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved'    => SupportTicket::where('status', 'resolved')->count(),
            'closed'      => SupportTicket::where('status', 'closed')->count(),
        ];

        $admins = User::where('role', User::ROLE_ADMIN)->get();

        return view('admin.support-tickets.index', compact(
            'tickets', 'counts',
            'status', 'type', 'priority', 'search', 'assignedTo', 'admins'
        ));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load([
            'user', 'booking.service', 'booking.branch',
            'messages.sender', 'messages.attachments',
        ]);

        $statuses   = SupportTicket::STATUSES;
        $priorities = SupportTicket::PRIORITIES;
        $types      = SupportTicket::TYPES;

        $admins = User::where('role', User::ROLE_ADMIN)->get();
        $cannedResponses = CannedResponse::orderBy('title')->get();

        return view('admin.support-tickets.show', compact(
            'supportTicket', 'statuses', 'priorities', 'types', 'admins', 'cannedResponses'
        ));
    }

    public function sendMessage(SendMessageRequest $request, SupportTicket $supportTicket)
    {
        $isInternal = $request->boolean('is_internal');

        if (!$isInternal && !$supportTicket->canReceiveMessages()) {
            return back()->with('error', 'This ticket is closed or locked.');
        }

        $message = TicketMessage::create([
            'ticket_id'   => $supportTicket->id,
            'sender_id'   => auth()->id(),
            'sender_role' => 'admin',
            'body'        => $request->body,
            'is_internal' => $isInternal,
        ]);

        if ($request->hasFile('attachments')) {
            $this->storeAttachments($request->file('attachments'), $supportTicket, $message);
        }

        if (in_array($supportTicket->status, [SupportTicket::STATUS_OPEN, SupportTicket::STATUS_RESOLVED]) && !$isInternal) {
            $supportTicket->update(['status' => SupportTicket::STATUS_IN_PROGRESS]);
        }

        if (!$isInternal) {
            $customer = User::find($supportTicket->user_id);
            if ($customer) {
                $customer->notify(new SupportTicketReplied($supportTicket));
            }
        }

        return redirect()
            ->route('admin.support-tickets.show', $supportTicket)
            ->with('success', $isInternal ? 'Internal note added.' : 'Reply sent and customer notified.')
            ->withFragment('messages-bottom');
    }

    public function editMessage(Request $request, TicketMessage $message)
    {
        if ($message->sender_id !== auth()->id() || $message->sender_role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'body' => ['required', 'string', 'min:1', 'max:5000'],
        ]);

        $message->update([
            'body'      => $request->body,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        return back()->with('success', 'Message updated successfully.');
    }

    public function assign(Request $request, SupportTicket $supportTicket)
    {
        $request->validate([
            'assigned_to' => ['nullable', \Illuminate\Validation\Rule::exists('users', 'id')->where('role', User::ROLE_ADMIN)],
        ]);

        $supportTicket->update([
            'assigned_to' => $request->assigned_to,
        ]);

        $message = $request->assigned_to ? 'Ticket assigned successfully.' : 'Ticket unassigned.';
        return back()->with('success', $message);
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        $request->validate(['status' => 'required|in:' . implode(',', array_keys(SupportTicket::STATUSES))]);
        $supportTicket->update(['status' => $request->status]);
        return back()->with('success', 'Status updated successfully.');
    }

    public function updatePriority(Request $request, SupportTicket $supportTicket)
    {
        $request->validate(['priority' => 'required|in:' . implode(',', array_keys(SupportTicket::PRIORITIES))]);
        $supportTicket->update(['priority' => $request->priority]);
        return back()->with('success', 'Priority updated successfully.');
    }

    public function updateTags(Request $request, SupportTicket $supportTicket)
    {
        $request->validate(['tags' => 'nullable|string']);
        $tags = array_filter(array_map('trim', explode(',', $request->tags ?? '')));
        $supportTicket->update(['tags' => empty($tags) ? null : $tags]);
        return back()->with('success', 'Tags updated successfully.');
    }

    public function toggleLock(SupportTicket $supportTicket)
    {
        $supportTicket->update(['is_locked' => !$supportTicket->is_locked]);
        $msg = $supportTicket->is_locked ? 'Ticket has been locked.' : 'Ticket has been unlocked.';
        return back()->with('success', $msg);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ticket_ids'   => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
            'action'       => 'required|string',
            'assigned_to'  => ['required_if:action,assign', 'nullable', \Illuminate\Validation\Rule::exists('users', 'id')->where('role', User::ROLE_ADMIN)],
        ]);

        $query = SupportTicket::whereIn('id', $request->ticket_ids);

        switch ($request->action) {
            case 'close':
                $query->update(['status' => SupportTicket::STATUS_CLOSED]);
                $msg = 'Selected tickets have been closed.';
                break;
            case 'resolve':
                $query->update(['status' => SupportTicket::STATUS_RESOLVED]);
                $msg = 'Selected tickets have been marked as resolved.';
                break;
            case 'assign':
                $query->update(['assigned_to' => $request->assigned_to]);
                $msg = 'Selected tickets have been assigned.';
                break;
            default:
                return back()->with('error', 'Invalid bulk action.');
        }

        return back()->with('success', $msg);
    }

    private function storeAttachments(array $files, SupportTicket $ticket, TicketMessage $message): void
    {
        foreach ($files as $file) {
            $directory  = "support-attachments/{$ticket->id}";
            $storedName = $file->hashName();
            $path       = $file->storeAs($directory, $storedName, 'public');

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
