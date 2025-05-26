<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'title', 'color', 'status', 'age', 'birth_date', 'cover_type', 'eye_color', 'shajare',
        'breed_id', 'father_name', 'father_breed_id', 'mother_name', 'mother_breed_id',
        'maternal_grandmother_name', 'maternal_grandmother_breed_id',
        'maternal_grandfather_name', 'maternal_grandfather_breed_id',
        'paternal_grandmother_name', 'paternal_grandmother_breed_id',
        'paternal_grandfather_name', 'paternal_grandfather_breed_id',
        'user_id',
    ];

    public function breed()
    {
        return $this->belongsTo(Breed::class, 'breed_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function owner()
    {
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
