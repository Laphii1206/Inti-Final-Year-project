<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
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
        $qrService = new \App\Services\QrCodeService();
        $qrUrl = $qrService->generate($this->booking);

        return (new MailMessage)
            ->subject('Booking Confirmed - #' . $this->booking->number)
            ->line('Your booking has been confirmed.')
            ->line('Service: ' . $this->booking->service->name)
            ->line('Date: ' . $this->booking->booking_date->format('Y-m-d'))
            ->line('Time: ' . \Carbon\Carbon::parse($this->booking->start_time)->format('H:i'))
            ->line('Please present the QR code when you arrive at the workshop.')
            ->action('View Booking', route('bookings.show', $this->booking->uuid))
            ->line('Thank you for choosing TRB Auto Care!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_uuid' => $this->booking->uuid,
            'title' => 'Booking Confirmed',
            'message' => 'Your booking #' . $this->booking->number . ' has been confirmed.',
            'url' => route('bookings.show', $this->booking->uuid),
        ];
    }
}
