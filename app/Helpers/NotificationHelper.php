<?php

namespace App\Helpers;

class NotificationHelper
{
    public static function format($notification)
    {
        $data = is_array($notification) ? $notification : ($notification->data ?? []);
        $title = $data['title'] ?? 'Notification';
        $message = $data['message'] ?? '';
        $url = $data['url'] ?? '#';

        // Translate Title
        if (str_starts_with($title, 'Booking Cancelled')) {
            $title = __('landing.notif_booking_cancelled');
        } elseif (str_starts_with($title, 'Booking Confirmed')) {
            $title = __('landing.notif_booking_confirmed');
        } elseif (str_starts_with($title, 'Booking Status Update')) {
            $title = __('landing.notif_booking_update');
        } elseif (preg_match('/^Support Ticket Update\s*—\s*Ticket #(.*)$/i', $title, $m)) {
            $title = __('landing.notif_ticket_update') . ' — Ticket #' . trim($m[1]);
        } elseif (preg_match('/^New Ticket #(.*)$/i', $title, $m)) {
            $title = __('landing.notif_new_ticket') . ' #' . trim($m[1]);
        } elseif (preg_match('/^New Message\s*—\s*Ticket #(.*)$/i', $title, $m)) {
            $title = __('landing.notif_new_msg') . ' — Ticket #' . trim($m[1]);
        } elseif ($title === 'Welcome to TRB Auto Care!') {
            $title = __('landing.notif_welcome_title');
        } elseif (str_contains($title, 'Happy Birthday') || str_contains($title, 'Selamat Hari Jadi') || str_contains($title, '生日快乐') || str_contains($title, 'பிறந்தநாள் வாழ்த்துகள்')) {
            $title = __('landing.notif_birthday_title');
        }

        // Translate Message
        if (preg_match('/^Your booking #(.*?)\s+has been cancelled\.$/i', $message, $m)) {
            $message = __('landing.notif_booking_cancelled_msg', ['number' => trim($m[1])]);
        } elseif (preg_match('/^Your booking #(.*?)\s+has been confirmed\.$/i', $message, $m)) {
            $message = __('landing.notif_booking_confirmed_msg', ['number' => trim($m[1])]);
        } elseif (preg_match('/^Your booking #(.*?)\s+status is now (.*?)\.$/i', $message, $m)) {
            $statusMap = [
                'confirmed' => __('landing.status_confirmed'),
                'cancelled' => __('landing.status_cancelled'),
                'completed' => __('landing.status_completed'),
                'in progress' => __('landing.status_in_progress'),
                'pending' => __('landing.status_pending'),
            ];
            $st = strtolower(trim($m[2]));
            $translatedStatus = $statusMap[$st] ?? ucfirst($st);
            $message = __('landing.notif_booking_status_msg', ['number' => trim($m[1]), 'status' => $translatedStatus]);
        } elseif (preg_match('/^Your support ticket has been updated\.\s*Status:\s*(.*?)\.\s*Click to view reply\.$/i', $message, $m)) {
            $statusMap = [
                'In Progress' => __('landing.status_in_progress'),
                'Resolved' => __('landing.status_resolved'),
                'Closed' => __('landing.status_closed'),
                'Open' => __('landing.status_open'),
            ];
            $st = trim($m[1]);
            $translatedStatus = $statusMap[$st] ?? $st;
            $message = __('landing.notif_ticket_msg', ['status' => $translatedStatus]);
        } elseif ($message === 'Thank you for registering. You can now book services and manage your vehicles.') {
            $message = __('landing.notif_welcome_msg');
        } elseif (($data['type'] ?? '') === 'birthday' || str_contains($message, 'birthday gift to you') || str_contains($message, 'hadiah hari jadi') || str_contains($message, '生日礼物') || str_contains($message, 'பிறந்தநாள் பரிசு') || str_contains($message, 'double points on all vehicle')) {
            if (($data['tier'] ?? '') === 'bronze' || str_contains($message, 'double points on all vehicle') || str_contains($message, '双倍积分')) {
                $message = __('landing.notif_birthday_bronze_msg');
            } else {
                $gift = '1 RM 50.00 Birthday Voucher & 100 Points';
                if (isset($data['message']) && preg_match('/added (.*?) to your/i', $data['message'], $m)) {
                    $gift = trim($m[1]);
                }
                $message = __('landing.notif_birthday_msg', ['gift' => $gift]);
            }
        }

        return [
            'title' => $title,
            'message' => $message,
            'url' => $url,
        ];
    }
}
