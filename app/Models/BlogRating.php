<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogRating extends Model
{
    protected $table = 'blog_ratings';

    protected $fillable = [
        'blog_id',
        'rating',
        'ip',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
