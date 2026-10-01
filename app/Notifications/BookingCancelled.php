<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelled extends Notification
{
    use Queueable;

    public $booking;

    public function __construct(\App\Models\Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking ' . ucfirst($this->booking->status) . ' - #' . $this->booking->number)
            ->line('Your booking has been ' . $this->booking->status . '.')
            ->line('Reason: ' . $this->booking->cancellation_reason)
            ->action('View Booking', route('bookings.show', $this->booking->uuid))
            ->line('If you have any questions, please contact us.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_uuid' => $this->booking->uuid,
            'title' => 'Booking ' . ucfirst($this->booking->status),
            'message' => 'Your booking #' . $this->booking->number . ' has been ' . $this->booking->status . '.',
            'url' => route('bookings.show', $this->booking->uuid),
        ];
    }
}
