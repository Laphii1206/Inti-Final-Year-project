<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    public const TIER_BRONZE = 'bronze';
    public const TIER_SILVER = 'silver';
    public const TIER_GOLD   = 'gold';

    public const SILVER_THRESHOLD = 500.00;
    public const GOLD_THRESHOLD   = 1500.00;

    public const BRONZE_MULTIPLIER = 1.0;
    public const SILVER_MULTIPLIER = 1.25;
    public const GOLD_MULTIPLIER   = 1.5;

    public const CHECKIN_POINTS       = 5;
    public const CHECKIN_STREAK_BONUS = 30;
    public const CHECKIN_STREAK_DAYS  = 7;

    public const SPIN_COST = 100;
    public const MAX_SPINS_PER_DAY = 5;

    protected $fillable = [
        'user_id', 'membership_id', 'tier', 'reward_points',
        'cumulative_annual_spending', 'membership_join_date',
        'last_tier_evaluated_at', 'referral_code',
    ];

    protected $casts = [
        'membership_join_date'    => 'date',
        'last_tier_evaluated_at'  => 'datetime',
        'reward_points'           => 'integer',
        'cumulative_annual_spending' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class, 'user_id', 'user_id');
    }

    public function getMultiplier(): float
    {
        return match($this->tier) {
            self::TIER_GOLD   => self::GOLD_MULTIPLIER,
            self::TIER_SILVER => self::SILVER_MULTIPLIER,
            default           => self::BRONZE_MULTIPLIER,
        };
    }

    public function getTierColor(): string
    {
        return match($this->tier) {
            self::TIER_GOLD   => 'warning',
            self::TIER_SILVER => 'secondary',
            default           => 'orange',
        };
    }

    public function getTierIcon(): string
    {
        return match($this->tier) {
            self::TIER_GOLD   => 'fa-crown',
            self::TIER_SILVER => 'fa-medal',
            default           => 'fa-shield',
        };
    }

    public function getSpendingToNextTier(): ?float
    {
        return match($this->tier) {
            self::TIER_BRONZE => max(0, self::SILVER_THRESHOLD - $this->cumulative_annual_spending),
            self::TIER_SILVER => max(0, self::GOLD_THRESHOLD - $this->cumulative_annual_spending),
            default           => null,
        };
    }

    public function getTierProgress(): int
    {
        return match($this->tier) {
            self::TIER_BRONZE => (int) min(100, ($this->cumulative_annual_spending / self::SILVER_THRESHOLD) * 100),
            self::TIER_SILVER => (int) min(100, ($this->cumulative_annual_spending / self::GOLD_THRESHOLD) * 100),
            default           => 100,
        };
    }

    public function getTierLabel(): string
    {
        return ucfirst($this->tier);
    }

    public function getNextTierLabel(): string
    {
        return match($this->tier) {
            self::TIER_BRONZE => 'Silver',
            self::TIER_SILVER => 'Gold',
            default           => 'Gold',
        };
    }
}
