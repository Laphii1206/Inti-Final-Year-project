<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpinResult extends Model
{
    protected $fillable = [
        'user_id', 'points_spent', 'reward_type',
        'reward_points', 'voucher_id', 'reward_label',
    ];

    protected $casts = [
        'reward_points' => 'integer',
        'points_spent'  => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
