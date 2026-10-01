<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATUS_PENDING  = 'pending';
    public const STATUS_PAID     = 'paid';
    public const STATUS_FAILED   = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'booking_id',
        'user_id',
        'gateway',
        'reference',
        'amount',
        'currency',
        'status',
        'meta',
        'paid_at',
    ];

    protected $casts = [
        'meta'    => 'array',
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
