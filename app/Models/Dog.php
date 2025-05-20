<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $perPage = 20;

    // ارتباط با مدل City
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    // ارتباط با مدل Specialty
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
    public function owner(){
        return $this->belongsTo(User::class);
    }
}
