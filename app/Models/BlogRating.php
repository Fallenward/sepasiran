<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BlogRating
 *
 * @property $id
 * @property $blog_id
 * @property $rating
 * @property $ip
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BlogRating extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['blog_id', 'rating', 'ip'];


}
