<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mission extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'reward_points', 'icon',
        'trigger_type', 'trigger_value', 'is_repeatable', 'is_active',
    ];

    protected $casts = [
        'trigger_value' => 'decimal:2',
        'is_repeatable' => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function userMissions(): HasMany
    {
        return $this->hasMany(UserMission::class);
    }

    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_missions')
                    ->withPivot(['points_awarded', 'completed_at'])
                    ->withTimestamps();
    }

    public function isCompletedByUser(int $userId): bool
    {
        return $this->userMissions()->where('user_id', $userId)->exists();
    }

    public function completionCountForUser(int $userId): int
    {
        return $this->userMissions()->where('user_id', $userId)->count();
    }
}
