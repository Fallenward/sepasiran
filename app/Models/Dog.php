<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dog extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'title', 'image', 'color', 'status', 'age', 'birth_date', 'cover_type', 'eye_color', 'shajare',
        'breed_id', 'father_name', 'father_breed_id', 'mother_name', 'mother_breed_id',
        'maternal_grandmother_name', 'maternal_grandmother_breed_id',
        'maternal_grandfather_name', 'maternal_grandfather_breed_id',
        'paternal_grandmother_name', 'paternal_grandmother_breed_id',
        'paternal_grandfather_name', 'paternal_grandfather_breed_id',
        'user_id',
    ];

    // Main breed
    public function breed()
    {
        return $this->belongsTo(Breed::class, 'breed_id');
    }

    // City relation (you have city_id in migration but missing in fillable, consider adding if used)
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Owner relation (User)
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(DogMedia::class)->latest();
    }

    public function primaryImageUrl(): ?string
    {
        $first = $this->relationLoaded('media')
            ? $this->media->first()
            : $this->media()->first();

        if ($first) {
            return $first->publicUrl();
        }

        if (! empty($this->image)) {
            if (str_starts_with($this->image, 'http') || str_starts_with($this->image, '/')) {
                return $this->image;
            }

            return asset($this->image);
        }

        return null;
    }

    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }

    // Father breed
    public function fatherBreed()
    {
        return $this->belongsTo(Breed::class, 'father_breed_id');
    }

    // Mother breed
    public function motherBreed()
    {
        return $this->belongsTo(Breed::class, 'mother_breed_id');
    }

    // Maternal grandmother breed
    public function maternalGrandmotherBreed()
    {
        return $this->belongsTo(Breed::class, 'maternal_grandmother_breed_id');
    }

    // Maternal grandfather breed
    public function maternalGrandfatherBreed()
    {
        return $this->belongsTo(Breed::class, 'maternal_grandfather_breed_id');
    }

    // Paternal grandmother breed
    public function paternalGrandmotherBreed()
    {
        return $this->belongsTo(Breed::class, 'paternal_grandmother_breed_id');
    }

    // Paternal grandfather breed
    public function paternalGrandfatherBreed()
    {
        return $this->belongsTo(Breed::class, 'paternal_grandfather_breed_id');
    }

    // User relation (alias for owner?)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
