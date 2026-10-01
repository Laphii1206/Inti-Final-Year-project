<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BirthdayNotification extends Notification
{
    use Queueable;

    public $vouchers;
    public $points;
    public $tier;

    public function __construct($vouchers = null, int $points = 100, string $tier = 'silver')
    {
        $this->vouchers = $vouchers;
        $this->points = $points;
        $this->tier = strtolower($tier);
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $locale = $notifiable->language ?? app()->getLocale();
        $mail = (new MailMessage)
            ->subject(__('landing.notif_birthday_title', [], $locale))
            ->greeting("Happy Birthday, {$notifiable->name}! 🎉")
            ->line("Wishing you a wonderful birthday filled with joy, happiness, and great journeys!");

        if ($this->tier === 'bronze') {
            return $mail
                ->line("As our special birthday celebration for you, enjoy 2X Double Points on all your vehicle servicing and bookings throughout your entire birthday month!")
                ->action('Book Service Now', route('bookings.create'))
                ->line("Upgrade to Silver or Gold tier to unlock up to 3 RM 50.00 Birthday Vouchers plus bonus points every birthday!");
        }

        $voucherCount = is_array($this->vouchers) || $this->vouchers instanceof \Countable ? count($this->vouchers) : ($this->vouchers ? 1 : 0);
        $voucherText = $voucherCount > 1 ? "{$voucherCount} RM 50.00 Birthday Vouchers" : "1 RM 50.00 Birthday Voucher";
        $giftDescription = $voucherCount > 0 
            ? "{$voucherText} & {$this->points} Bonus Points"
            : "{$this->points} Bonus Points";

        return $mail
            ->line("As our special gift to celebrate your birthday as a valued " . ucfirst($this->tier) . " VIP member, we have added {$giftDescription} to your account, plus enjoy 2X Double Points on all bookings during your birthday month!")
            ->action('View Your Birthday Rewards', route('vouchers.index'))
            ->line('Enjoy your special day and thank you for being a valued member of TRB Auto Care!');
    }

    public function toArray(object $notifiable): array
    {
        $locale = $notifiable->language ?? app()->getLocale();

        if ($this->tier === 'bronze') {
            return [
                'title'   => __('landing.notif_birthday_title', [], $locale),
                'message' => __('landing.notif_birthday_bronze_msg', [], $locale),
                'icon'    => 'fa-cake-candles',
                'type'    => 'birthday',
                'url'     => route('bookings.create'),
                'tier'    => 'bronze',
            ];
        }

        $voucherCount = is_array($this->vouchers) || $this->vouchers instanceof \Countable ? count($this->vouchers) : ($this->vouchers ? 1 : 0);
        $giftText = $voucherCount > 1 
            ? "{$voucherCount} RM 50.00 Birthday Vouchers & {$this->points} Points"
            : ($voucherCount == 1 ? "1 RM 50.00 Birthday Voucher & {$this->points} Points" : "{$this->points} Bonus Points");

        return [
            'title'   => __('landing.notif_birthday_title', [], $locale),
            'message' => __('landing.notif_birthday_msg', ['gift' => $giftText], $locale),
            'icon'    => 'fa-cake-candles',
            'type'    => 'birthday',
            'url'     => route('vouchers.index'),
            'tier'    => $this->tier,
        ];
    }
}
