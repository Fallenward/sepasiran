<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DogMedia extends Model
{
    protected $table = 'dog_media';

    protected $fillable = [
        'dog_id', 'type', 'path', 'url', 'filename', 'mime', 'size', 'caption',
    ];

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }

    public function publicUrl(): string
    {
        if (! empty($this->url)) {
            return $this->url;
        }

        return asset('storage/'.ltrim((string) $this->path, '/'));
    }
}