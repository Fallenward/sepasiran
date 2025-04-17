<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Discount
 *
 * @property $id
 * @property $name
 * @property $code
 * @property $role_id
 * @property $course_ids
 * @property $user_ids
 * @property $percentage
 * @property $use_count
 * @property $status
 * @property $start_date
 * @property $end_date
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Discount extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'code', 'role_id', 'course_ids', 'user_ids', 'percentage', 'use_count', 'status', 'start_date', 'end_date'];


}
