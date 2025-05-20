<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function dog(){
        return $this->belongsto(Dog::class);
    }

    public function city()
{
    return $this->belongsTo(User::class);
}
}
