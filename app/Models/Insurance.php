<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Insurance
 *
 * @property $id
 * @property $name
 * @property $slug
 * @property $description
 * @property $logo
 * @property $contact_phone
 * @property $contact_email
 * @property $website
 * @property $is_active
 * @property $is_featured
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Insurance extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'slug', 'description', 'logo', 'contact_phone', 'contact_email', 'website', 'is_active', 'is_featured'];


}
