<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'type', // 'purchase' or 'rental'
        'status', // 'pending', 'paid', 'shipped', 'delivered', 'cancelled'
        'items',
        'shipping_address',
        'contact_info',
        'payment_info',
        'subtotal',
        'total',
        'stripe_payment_intent_id',
        'rental_start_date',
        'rental_end_date',
        'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'shipping_address' => 'array',
        'contact_info' => 'array',
        'payment_info' => 'array',
        'subtotal' => 'float',
        'total' => 'float',
        'rental_start_date' => 'datetime',
        'rental_end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total, 2);
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . date('Y') . '-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}