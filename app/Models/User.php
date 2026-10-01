<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_CUSTOMER = 0;
    public const ROLE_MECHANIC = 1;
    public const ROLE_ADMIN    = 2;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
        'language', 'theme',
        'google_id', 'facebook_id', 'avatar',
        'referred_by', 'date_of_birth',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => 'integer',
            'is_main_admin'     => 'boolean', 
            'date_of_birth'     => 'date',
        ];
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isMechanic(): bool
    {
        return $this->role === self::ROLE_MECHANIC;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMainAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN && $this->is_main_admin === true;
    }

    public function isSubAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN && $this->is_main_admin === false;
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
        }
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=EC1F24&color=fff&size=64";
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function assignedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'assigned_staff_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function membership(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Membership::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function userMissions(): HasMany
    {
        return $this->hasMany(UserMission::class);
    }

    public function dailyCheckins(): HasMany
    {
        return $this->hasMany(DailyCheckin::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public function referrer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function webAuthnCredentials(): HasMany
    {
        return $this->hasMany(WebAuthnCredential::class);
    }

    public function favourites(): HasMany
    {
        return $this->hasMany(UserFavourite::class);
    }

    public function favouredServices(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'user_favourites')
                    ->withTimestamps();
    }
}
