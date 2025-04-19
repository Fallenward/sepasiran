<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 *
 * @property $id
 * @property $user_id
 * @property $course_id
 * @property $quantity
 * @property $price
 * @property $discount_id
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Cart extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PURCHASED = 'purchased';
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'course_id', 'quantity', 'price', 'discount_id', 'status'];


}
