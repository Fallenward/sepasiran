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
    public function insurance()
{
    return $this->belongsTo(Insurance::class);
}


public function fatherBreed()
{
    return $this->belongsTo(Breed::class, 'father_breed_id');
}

public function motherBreed()
{
    return $this->belongsTo(Breed::class, 'mother_breed_id');
}

public function maternalGrandmotherBreed()
{
    return $this->belongsTo(Breed::class, 'maternal_grandmother_breed_id');
}

public function maternalGrandfatherBreed()
{
    return $this->belongsTo(Breed::class, 'maternal_grandfather_breed_id');
}

public function paternalGrandmotherBreed()
{
    return $this->belongsTo(Breed::class, 'paternal_grandmother_breed_id');
}

public function paternalGrandfatherBreed()
{
    return $this->belongsTo(Breed::class, 'paternal_grandfather_breed_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}



}
