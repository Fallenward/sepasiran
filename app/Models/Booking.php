<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'booking_code',
        'user_id',
        'service_type',
        'provider_id',
        'city_id',
        'breed_id',
        'training_type',
        'appointment_date',
        'slot',
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
        'contact_name',
        'contact_phone',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = 'SP-'.random_int(100000, 999999);
        } while (static::query()->where('booking_code', $code)->exists());

        return $code;
    }

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

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'booking_cart', 'booking_id', 'cart_id')
            ->withTimestamps();
    }
}