<?php

namespace App\Services;

use App\Models\Booking;

class QrCodeService
{
    /*Generate a QR code URL pointing to the check-in scan route,Uses a free, secure QR code API to avoid composer package issues.*/
    public function generate(Booking $booking): string
    {
        $url = route('mechanic.checkin.scan', ['uuid' => $booking->uuid]);
        return "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url);
    }

    /*Get public URL of the QR code.*/
    public function getPublicUrl(Booking $booking): ?string
    {
        return $this->generate($booking);
    }

    /*Delete QR code placeholder (no-op since it is generated on the fly).*/
    public function delete(Booking $booking): void
    {
        // No-op
    }
}
