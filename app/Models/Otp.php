<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public function user(){
        return $this->belongsto(User::class ,'User_id');
    }
    //
}
