<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCheckin extends Model
{
    protected $fillable = [
        'user_id', 'checked_in_date', 'streak_count', 'points_awarded', 'streak_bonus_awarded',
    ];

    protected $casts = [
        'checked_in_date'      => 'date:Y-m-d',
        'streak_bonus_awarded' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getStreakForUser(User $user): int
    {
        $today     = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $last = static::where('user_id', $user->id)
            ->orderByDesc('checked_in_date')
            ->first();

        if (!$last) return 0;

        $lastDate = is_string($last->checked_in_date)
            ? substr($last->checked_in_date, 0, 10)
            : $last->checked_in_date->toDateString();

        if ($lastDate === $today)     return $last->streak_count;
        if ($lastDate === $yesterday) return $last->streak_count;

        return 0;
    }

    public static function hasCheckedInToday(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->whereDate('checked_in_date', now()->toDateString())
            ->exists();
    }
}
