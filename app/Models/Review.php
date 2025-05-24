<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['blog_id', 'user_id', 'rating', 'comment'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

}
