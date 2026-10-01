<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusUpdate extends Notification
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
            ->subject('Booking Status Update - #' . $this->booking->number)
            ->line('Your booking status has been updated to: ' . ucfirst(str_replace('_', ' ', $this->booking->status)))
            ->action('View Booking', route('bookings.show', $this->booking->uuid))
            ->line('Thank you for choosing TRB Auto Care!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_uuid' => $this->booking->uuid,
            'title' => 'Booking Status Update',
            'message' => 'Your booking #' . $this->booking->number . ' status is now ' . ucfirst(str_replace('_', ' ', $this->booking->status)) . '.',
            'url' => route('bookings.show', $this->booking->uuid),
        ];
    }
}
