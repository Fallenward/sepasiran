<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'contact_phone',
        'contact_email',
        'website',
        'is_active',
        'is_featured',
    ];

    // Relationship: An insurance can have many dogs
    public function dogs()
    {
        return $this->hasMany(Dog::class);
    }

    // In Insurance.php
public function city()
{
    return $this->belongsTo(City::class);
}

}
