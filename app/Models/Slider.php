<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Slider
 *
 * @property $id
 * @property $title
 * @property $image
 * @property $link
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Slider extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'image', 'link'];


}
