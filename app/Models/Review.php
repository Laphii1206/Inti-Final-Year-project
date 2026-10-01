<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'rating',
        'comment',
        'is_visible', 
    ];

    protected $casts = [
        'rating'     => 'integer',
        'is_visible' => 'boolean',
    ];

    
    // (Relationships) 评价属于哪个预约
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // 评价是谁写的
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

      public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }
}