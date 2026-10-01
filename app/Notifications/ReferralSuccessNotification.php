<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferralSuccessNotification extends Notification
{
    use Queueable;

    public function __construct(public string $friendName, public int $pointsEarned)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Referral Bonus!',
            'message' => "Your friend {$this->friendName} just completed their first booking! You've earned {$this->pointsEarned} points.",
            'icon' => 'fa-gift',
            'type' => 'success',
            'points' => $this->pointsEarned
        ];
    }
}
