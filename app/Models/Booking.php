<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'user_id',
        'service_type',
        'provider_id',
        'city_id',
        'appointment_date',
        'booking_date',
        'price',
        'tax',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'transaction_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'booking_cart', 'booking_id', 'cart_id')
            ->withTimestamps();
    }
}