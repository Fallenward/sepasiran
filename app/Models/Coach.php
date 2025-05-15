<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;
        public function user()
    {
        return $this->belongsTo(User::class);
    }


    // تعریف روابط
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function courses()
{
    return $this->hasMany(Course::class);
}

}
