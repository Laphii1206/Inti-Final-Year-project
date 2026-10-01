<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeletionApproved extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Deletion Approved')
            ->line('Your request to delete your account has been approved.')
            ->line('Your account has been deleted and all your personal data has been removed or anonymized according to our policies.')
            ->line('We are sorry to see you go. If you have any questions or would like to restore your account in the future, please contact our support team.');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
