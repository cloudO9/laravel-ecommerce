<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class GameStatus extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'game_statuses';

    protected $fillable = [
        'game_id',
        'status', // 'available', 'rented', 'sold'
        'rented_to_user_id',
        'rental_start_date',
        'rental_end_date',
        'order_id'
    ];

    protected $casts = [
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function renter()
    {
        return $this->belongsTo(User::class, 'rented_to_user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}