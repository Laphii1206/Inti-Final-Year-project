<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class MembershipTierUpgraded extends Notification
{
    use Queueable;

    public function __construct(public string $newTier) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $tierLabel = ucfirst($this->newTier);
        return [
            'title'   => "🎉 Membership Upgraded to {$tierLabel}!",
            'message' => "Congratulations! You've been upgraded to {$tierLabel} tier membership. Enjoy your new benefits!",
            'type'    => 'membership_upgrade',
            'tier'    => $this->newTier,
        ];
    }
}
