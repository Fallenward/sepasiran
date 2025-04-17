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
    protected $fillable = ['user_id', 'service_type', 'provider_id', 'city_id', 'appointment_date', 'status', 'notes'];


}
