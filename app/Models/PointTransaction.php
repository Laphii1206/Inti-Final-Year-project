<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PointTransaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'points', 'description',
        'reference_type', 'reference_id',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeColor(): string
    {
        return match($this->type) {
            'earn', 'bonus'  => 'success',
            'redeem', 'spin' => 'warning',
            'expire'         => 'danger',
            'admin_adjust'   => 'info',
            default          => 'secondary',
        };
    }

    public function getTypeIcon(): string
    {
        return match($this->type) {
            'earn'         => 'fa-circle-plus',
            'bonus'        => 'fa-star',
            'redeem'       => 'fa-ticket',
            'spin'         => 'fa-rotate',
            'expire'       => 'fa-clock',
            'admin_adjust' => 'fa-user-shield',
            default        => 'fa-circle',
        };
    }
}
