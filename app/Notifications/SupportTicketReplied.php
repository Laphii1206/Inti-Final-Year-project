<?php

namespace App\Notifications;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketReplied extends Notification
{
    use Queueable;

    public function __construct(public SupportTicket $ticket) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($notifiable->isAdmin()) {
            $isNew = $this->ticket->messages()->count() <= 1;
            return [
                'ticket_number' => $this->ticket->ticket_number,
                'title'         => $isNew ? 'New Ticket #' . $this->ticket->ticket_number : 'New Message — Ticket #' . $this->ticket->ticket_number,
                'message'       => $isNew
                    ? 'Customer ' . ($this->ticket->user->name ?? '') . ' created a new ticket: "' . \Illuminate\Support\Str::limit($this->ticket->subject, 35) . '". Priority: ' . $this->ticket->getPriorityLabel() . '.'
                    : 'Customer ' . ($this->ticket->user->name ?? '') . ' has replied to their ticket. Priority: ' . $this->ticket->getPriorityLabel() . '.',
                'url'           => route('admin.support-tickets.show', $this->ticket),
            ];
        }

        return [
            'ticket_number' => $this->ticket->ticket_number,
            'title'         => 'Support Ticket Update — Ticket #' . $this->ticket->ticket_number,
            'message'       => 'Your support ticket has been updated. Status: ' . $this->ticket->getStatusLabel() . '. Click to view reply.',
            'url'           => route('support-tickets.show', $this->ticket->ticket_number),
        ];
    }
}
