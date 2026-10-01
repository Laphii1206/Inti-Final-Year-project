<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'description',
        'category',
        'price',
        'estimated_duration',
        'meta_data',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'meta_data'          => 'array',
        'price'              => 'decimal:2',
        'estimated_duration' => 'integer',
        'is_active'          => 'boolean',
        'deleted_at'         => 'datetime',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

       public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    
    public function scopeBrand($query, $brand)
    {
        if ($brand) {
            return $query->where('meta_data->brand', $brand);
        }
    }

    public function scopeTyreSize($query, $tyreSize)
    {
        if ($tyreSize) {
            return $query->where('meta_data->tyre_size', $tyreSize);
        }
    }

    public function scopePriceRange($query, $min, $max)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->whereRaw('LOWER(category) = ?', [strtolower($category)]);
        }
    }

    public function scopeKeyword($query, $keyword)
    {
        if ($keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%');
        }
    }
}