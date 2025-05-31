<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Game extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'games';

    protected $fillable = [
        'name',
        'image',
        'is_for_rent',
        'rent_price',
        'sell_price',
        'condition',
        'description',
        'seller_id',
    ];

    protected $casts = [
        'is_for_rent' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Custom accessors and mutators to handle price casting safely
    public function getRentPriceAttribute($value)
    {
        return $value !== null ? (float) $value : null;
    }

    public function setRentPriceAttribute($value)
    {
        $this->attributes['rent_price'] = $value !== null ? (float) $value : null;
    }

    public function getSellPriceAttribute($value)
    {
        return $value !== null ? (float) $value : null;
    }

    public function setSellPriceAttribute($value)
    {
        $this->attributes['sell_price'] = $value !== null ? (float) $value : null;
    }

    // Relationship with User (seller)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Helper method to get the price based on type
    public function getPrice()
    {
        return $this->is_for_rent ? $this->rent_price : $this->sell_price;
    }

    // Helper method to get price with currency and period
    public function getFormattedPrice()
    {
        if ($this->is_for_rent && $this->rent_price !== null) {
            return '$' . number_format($this->rent_price, 2) . '/day';
        } elseif (!$this->is_for_rent && $this->sell_price !== null) {
            return '$' . number_format($this->sell_price, 2);
        }
        return 'Price not set';
    }

    // Helper method to get the type
    public function getType()
    {
        return $this->is_for_rent ? 'rent' : 'sale';
    }

    // Helper method to get display type
    public function getTypeDisplay()
    {
        return $this->is_for_rent ? 'For Rent' : 'For Sale';
    }

    // Validation helper
    public function hasValidPrice()
    {
        if ($this->is_for_rent) {
            return !is_null($this->rent_price) && $this->rent_price > 0;
        } else {
            return !is_null($this->sell_price) && $this->sell_price > 0;
        }
    }

    // Scope for filtering by type
    public function scopeForSale($query)
    {
        return $query->where('is_for_rent', false);
    }

    public function scopeForRent($query)
    {
        return $query->where('is_for_rent', true);
    }

    // Scope for filtering by condition
    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    // Scope for price range filtering
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where(function ($q) use ($min) {
                $q->where(function ($subQ) use ($min) {
                    $subQ->where('is_for_rent', false)
                         ->where('sell_price', '>=', (float) $min);
                })->orWhere(function ($subQ) use ($min) {
                    $subQ->where('is_for_rent', true)
                         ->where('rent_price', '>=', (float) $min);
                });
            });
        }

        if ($max !== null) {
            $query->where(function ($q) use ($max) {
                $q->where(function ($subQ) use ($max) {
                    $subQ->where('is_for_rent', false)
                         ->where('sell_price', '<=', (float) $max);
                })->orWhere(function ($subQ) use ($max) {
                    $subQ->where('is_for_rent', true)
                         ->where('rent_price', '<=', (float) $max);
                });
            });
        }

        return $query;
    }



    public function status()
{
    return $this->hasOne(GameStatus::class, 'game_id');
}

public function isAvailable()
{
    $status = $this->status;
    return !$status || $status->status === 'available';
}

public function isRented()
{
    $status = $this->status;
    return $status && $status->status === 'rented';
}

public function isSold()
{
    $status = $this->status;
    return $status && $status->status === 'sold';
}

public function getStatusDisplay()
{
    if ($this->isSold()) return '❌ Sold';
    if ($this->isRented()) return '📅 Rented';
    return '✅ Available';
}

public function getStatusColor()
{
    if ($this->isSold()) return 'text-red-400';
    if ($this->isRented()) return 'text-yellow-400';
    return 'text-green-400';
}
}