<?php

namespace App\Models;

use App\Events\BookingStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_NO_SHOW = 'no_show';

    public const STANDARD_SERVICE_CATEGORIES = ['tyre', 'wiper', 'engine oil'];

    public const ACTIVE_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_IN_PROGRESS];
    public const PAST_STATUSES = [self::STATUS_COMPLETED, self::STATUS_CANCELLED, self::STATUS_REJECTED, self::STATUS_NO_SHOW];
    public const CANCELLABLE_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED];

    protected $fillable = [
        'uuid', 'number', 'user_id', 'car_id', 'service_id', 'branch_id',
        'assigned_staff_id', 'service_price_at_booking', 'booking_date',
        'start_time', 'end_time', 'status', 'customer_remark',
        'is_rescheduled', 'cancellation_reason', 'qr_scanned_at', 'recorded_mileage',
        'voucher_id', 'discount_amount', 'booking_options',
    ];

    protected $casts = [
        'booking_date'             => 'date',
        'start_time'               => 'datetime:H:i:s',
        'end_time'                 => 'datetime:H:i:s',
        'qr_scanned_at'            => 'datetime',
        'service_price_at_booking' => 'decimal:2',
        'discount_amount'          => 'decimal:2',
        'recorded_mileage'         => 'integer',
        'is_rescheduled'           => 'boolean',
        'booking_options'          => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function booted(): void
    {
        static::creating(function (self $booking): void {
            if (empty($booking->uuid)) $booking->uuid = (string) Str::uuid();
            if (empty($booking->number)) $booking->number = $booking->generateBookingNumber();
        });

        static::updated(function (self $booking): void {
            if ($booking->wasChanged('status')) {
                BookingStatusChanged::dispatch($booking, $booking->getOriginal('status'));
            }
        });
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class)->withTrashed(); }
    public function car(): BelongsTo { return $this->belongsTo(Car::class)->withTrashed(); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class)->withTrashed(); }
    public function branch(): BelongsTo { return $this->belongsTo(Branch::class)->withTrashed(); }
    
    // 技师关联
    public function assignedStaff(): BelongsTo { return $this->belongsTo(User::class, 'assigned_staff_id')->withTrashed(); }
    public function voucher(): BelongsTo { return $this->belongsTo(Voucher::class); }
    
    public function review(): HasOne { return $this->hasOne(Review::class); }
    public function activityLogs(): MorphMany { return $this->morphMany(ActivityLog::class, 'loggable'); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }

    public function isPaid(): bool
    {
        return $this->payments()->where('status', Payment::STATUS_PAID)->exists();
    }

    public function processRefund(): void
    {
        $paidPayments = $this->payments()->where('status', Payment::STATUS_PAID)->get();
        foreach ($paidPayments as $payment) {
            if ($payment->gateway === 'stripe' && !empty($payment->meta['stripe_payment_intent'])) {
                try {
                    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                    \Stripe\Refund::create([
                        'payment_intent' => $payment->meta['stripe_payment_intent'],
                    ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Stripe refund failed for payment #' . $payment->id . ': ' . $e->getMessage());
                }
            }
            $payment->update(['status' => Payment::STATUS_REFUNDED]);
        }
    }

    public function restoreVoucher(): void
    {
        if ($this->voucher_id) {
            $voucher = \App\Models\Voucher::lockForUpdate()->find($this->voucher_id);
            if ($voucher && $voucher->status === 'used' && $voucher->used_in_booking_id === $this->id) {
                $voucher->update([
                    'status'             => 'available',
                    'used_at'            => null,
                    'used_in_booking_id' => null,
                    'current_uses'       => max(0, $voucher->current_uses - 1),
                ]);
            }
        }
    }

    public function generateBookingNumber(): string
    {
        $date = ($this->booking_date ?? now())->format('ymd');
        $pool = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789'; // Excludes l, I, i, L, o, O, 0, 1
        do {
            $random = substr(str_shuffle($pool), 0, 4);
            $candidate = $date . $random;
        } while (static::withTrashed()->where('number', $candidate)->exists());

        return $candidate;
    }

    public function scopeUpcoming($query) { return $query->whereIn('status', self::ACTIVE_STATUSES); }
    public function scopePast($query) { return $query->whereIn('status', self::PAST_STATUSES); }
    public function isToday(): bool { return $this->booking_date->isToday(); }
    public function getQrRouteUrl(): string { return route('mechanic.checkin.scan', ['uuid' => $this->uuid]); }

    public function hasQrCode(): bool
    {
        return $this->isPaid() && !in_array($this->status, ['cancelled', 'rejected', 'completed']);
    }

    public function getQrCodeUrl(): string
    {
        return (new \App\Services\QrCodeService())->generate($this);
    }
}