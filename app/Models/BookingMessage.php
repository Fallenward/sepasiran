<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingMessage extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'provider_id',
        'booking_offer_id',
        'body',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The provider side of this 1:1 thread (booking + provider). */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(BookingOffer::class, 'booking_offer_id');
    }
}