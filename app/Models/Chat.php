<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Chat
 *
 * @property $id
 * @property $created_at
 * @property $updated_at
 *
 * @property Chat[] $chats
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Chat extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chats()
    {
        return $this->hasMany(\App\Models\Chat::class, 'id', 'parent_id');
    }
    
}
