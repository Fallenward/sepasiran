<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // If your table name is different than the plural form of the model name, specify it like this:
    protected $table = 'posts';  // This should match the name of your table

    // If you want to allow mass assignment for these columns:
    protected $fillable = [
        'title', 'excerpt', 'body', 'image'
    ];

    // If you are using timestamps, ensure the following is included (it should be by default):
    public $timestamps = true;

    // If the table has custom timestamp column names, you can specify them like this:
    // const CREATED_AT = 'creation_date';
    // const UPDATED_AT = 'last_update';
}
