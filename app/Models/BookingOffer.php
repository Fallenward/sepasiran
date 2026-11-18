<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingOffer extends Model
{
    protected $fillable = [
        'booking_id',
        'provider_id',
        'price',
        'message',
        'proposed_date',
        'proposed_slot',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'proposed_date' => 'date',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
