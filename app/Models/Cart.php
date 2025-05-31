<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'cart_items';

    protected $fillable = [
        'user_id',
        'game_id',
        'quantity',
        'rental_days', // Only for rental items
        'added_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'rental_days' => 'integer',
        'added_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship with Game
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    // Helper method to get total price for this cart item
    public function getTotalPrice()
    {
        $game = $this->game;
        if (!$game) return 0;

        if ($game->is_for_rent) {
            return $game->rent_price * $this->rental_days * $this->quantity;
        } else {
            return $game->sell_price * $this->quantity;
        }
    }

    // Helper method to get formatted total price
    public function getFormattedTotalPrice()
    {
        return '$' . number_format($this->getTotalPrice(), 2);
    }

    // Scope to get cart items for a specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Static method to get cart count for user
    public static function getCountForUser($userId)
    {
        return static::where('user_id', $userId)->sum('quantity');
    }

    // Static method to get cart total for user
    public static function getTotalForUser($userId)
    {
        $cartItems = static::where('user_id', $userId)->get();
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item->getTotalPrice();
        }
        
        return $total;
    }

    // Static method to add item to cart or update existing
    public static function addToCart($userId, $gameId, $quantity = 1, $rentalDays = 1)
    {
        $existingItem = static::where('user_id', $userId)
                             ->where('game_id', $gameId)
                             ->first();

        if ($existingItem) {
            // Update existing item
            $existingItem->quantity += $quantity;
            if ($rentalDays > 1) {
                $existingItem->rental_days = $rentalDays;
            }
            $existingItem->save();
            return $existingItem;
        } else {
            // Create new cart item
            return static::create([
                'user_id' => $userId,
                'game_id' => $gameId,
                'quantity' => $quantity,
                'rental_days' => $rentalDays,
                'added_at' => now(),
            ]);
        }
    }
}