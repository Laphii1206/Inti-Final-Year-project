<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'user_id', 'code', 'type', 'value', 'terms_conditions', 'source', 'status',
        'points_cost', 'max_uses', 'current_uses',
        'expires_at', 'used_at', 'used_in_booking_id',
    ];

    protected $casts = [
        'value'      => 'decimal:2',
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function usedInBooking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'used_in_booking_id');
    }

    public function isValid(): bool
    {
        if ($this->status !== 'available') return false;
        if ($this->expires_at && now()->isAfter($this->expires_at)) return false;
        if ($this->current_uses >= $this->max_uses) return false;
        return true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    public function getValueLabel(): string
    {
        $formattedValue = $this->value == (int) $this->value
            ? number_format($this->value, 0)
            : number_format($this->value, 2);

        return $this->type === 'fixed'
            ? __('rewards.vouchers_rm') . ' ' . $formattedValue
            : $formattedValue . '%';
    }

    public function getDiscountLabel(): string
    {
        return __('rewards.vouchers_label_off', ['label' => $this->getValueLabel()]);
    }

    public function getSourceLabel(): string
    {
        return match($this->source) {
            'redeemed_points' => __('rewards.src_points_redemption'),
            'birthday'        => __('rewards.src_birthday_gift'),
            'promo_code'      => __('dashboard.rw_promo_code'),
            'spin_win'        => __('rewards.src_spin_win'),
            default           => ucfirst($this->source),
        };
    }

    public function getSourceColor(): string
    {
        return match($this->source) {
            'redeemed_points' => 'primary',
            'birthday'        => 'danger',
            'promo_code'      => 'success',
            'spin_win'        => 'warning',
            default           => 'secondary',
        };
    }

    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expires_at) return null;
        return (int) now()->diffInDays($this->expires_at, false);
    }

    public function getTermsAndConditions(): string
    {
        if (!empty($this->terms_conditions)) {
            if (str_contains($this->terms_conditions, 'Valid for all online bookings') || str_contains($this->terms_conditions, 'Points redemption voucher')) {
                return __('rewards.tnc_points_redemption');
            }
            if (str_contains($this->terms_conditions, 'Won via Spin & Win')) {
                return __('rewards.tnc_spin_win');
            }
            if (str_contains($this->terms_conditions, 'one-time use per customer') || str_contains($this->terms_conditions, 'Promo code voucher')) {
                return __('rewards.tnc_promo_code');
            }
            if (str_contains($this->terms_conditions, 'Birthday exclusive reward')) {
                return __('rewards.tnc_birthday');
            }
            return $this->terms_conditions;
        }

        return match($this->source) {
            'redeemed_points' => __('rewards.tnc_points_redemption'),
            'spin_win'        => __('rewards.tnc_spin_win'),
            'promo_code'      => __('rewards.tnc_promo_code'),
            'birthday'        => __('rewards.tnc_birthday'),
            default           => __('rewards.tnc_general'),
        };
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->where(function ($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }
}

