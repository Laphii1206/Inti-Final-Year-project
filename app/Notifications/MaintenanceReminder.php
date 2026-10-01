<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceReminder extends Notification
{
    use Queueable;

    public $car;
    public $reason;

    public function __construct(\App\Models\Car $car, string $reason)
    {
        $this->car = $car;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Maintenance Reminder: ' . $this->car->car_plate)
            ->line('It is time to service your vehicle: ' . $this->car->car_plate . ' (' . $this->car->brand . ' ' . $this->car->model . ').')
            ->line('Reason: ' . $this->reason)
            ->action('Book a Service', route('bookings.create'))
            ->line('Regular maintenance keeps your car running smoothly and safely.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'car_id' => $this->car->id,
            'title' => 'Maintenance Reminder',
            'message' => 'Time to service ' . $this->car->car_plate . '. ' . $this->reason,
            'url' => route('bookings.create'),
        ];
    }
}
