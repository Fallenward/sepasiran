<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'booking_code',
        'user_id',
        'dog_id',
        'service_type',
        'fulfillment_mode',
        'provider_id',
        'provider_service_id',
        'open_session_id',
        'open_session_application_id',
        'city_id',
        'breed_id',
        'training_type',
        'appointment_date',
        'slot',
        'preferred_date',
        'preferred_slot',
        'booking_date',
        'price',
        'tax',
        'discount',
        'total',
        'payment_method',
        'payment_status',
        'settlement_status',
        'held_amount',
        'transaction_id',
        'gateway_driver',
        'gateway_transaction_id',
        'status',
        'notes',
        'contact_name',
        'contact_phone',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'preferred_date' => 'date',
        'booking_date' => 'date',
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'held_amount' => 'integer',
    ];

    public function providerService()
    {
        return $this->belongsTo(ProviderService::class, 'provider_service_id');
    }

    public function isHeld(): bool
    {
        return (string) $this->settlement_status === 'held'
            && (string) $this->payment_status === 'paid';
    }

    public function isSettled(): bool
    {
        return (string) $this->settlement_status === 'settled';
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateCode();
            }
            if (empty($booking->fulfillment_mode)) {
                $booking->fulfillment_mode = 'direct';
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

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function invitedProviders()
    {
        return $this->belongsToMany(User::class, 'booking_providers', 'booking_id', 'provider_id')
            ->withTimestamps();
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function offers()
    {
        return $this->hasMany(BookingOffer::class);
    }

    public function messages()
    {
        return $this->hasMany(BookingMessage::class)->orderBy('id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'booking_cart', 'booking_id', 'cart_id')
            ->withTimestamps();
    }
}
