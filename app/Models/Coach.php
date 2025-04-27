<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

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
}
