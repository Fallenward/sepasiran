<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DogOrder extends Model
{
    protected $fillable = [
        'dog_id',
        'customer_name',
        'customer_phone',
        'notes',
        'status',
    ];

    public function dog()
    {
        return $this->belongsTo(Dog::class);
    }
}
