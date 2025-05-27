<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Booking
 *
 * @property $id
 * @property $user_id
 * @property $service_type
 * @property $provider_id
 * @property $city_id
 * @property $appointment_date
 * @property $status
 * @property $notes
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Booking extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'bookable_type', 'bookable_id', 'status', 'booking_date', 'notes'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookable() {
        return $this->morphTo();
    }
    public function carts()
{
    return $this->belongsToMany(Cart::class, 'booking_cart', 'booking_id', 'cart_id')
                ->withTimestamps();
}


    public function users() {
        return $this->belongsToMany(User::class, 'booking_users');
    }
}
