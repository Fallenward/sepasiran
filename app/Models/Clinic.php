<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    // Table name (if different from the default plural form of the model name)
    protected $table = 'clinics';

    // Fillable fields (or guarded to specify protected fields)
    protected $fillable = [
        'name', 'city_id', 'specialty_id', 'description',
    ];

    // Define relationships
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
