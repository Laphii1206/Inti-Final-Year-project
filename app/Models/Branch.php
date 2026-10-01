<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact_number',
        'address',
        'google_map_link',
        'opening_time',
        'closing_time',
        'service_capacity',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'opening_time'     => 'datetime:H:i',
        'closing_time'     => 'datetime:H:i',
        'service_capacity' => 'integer',
        'is_active'        => 'boolean',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isOpenNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now()->format('H:i:s');

        $open = $this->opening_time instanceof \DateTimeInterface
            ? $this->opening_time->format('H:i:s')
            : (string) $this->opening_time;

        $close = $this->closing_time instanceof \DateTimeInterface
            ? $this->closing_time->format('H:i:s')
            : (string) $this->closing_time;

        if ($open <= $close) {
            return $now >= $open && $now <= $close;
        }

        return $now >= $open || $now <= $close;
    }
}