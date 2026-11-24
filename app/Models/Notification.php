<?php

namespace App\Models;

use App\Support\AppCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'read_at'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        $forget = static function (self $notification): void {
            if ($notification->user_id) {
                AppCache::forgetUserHeader((int) $notification->user_id);
            }
        };

        static::saved($forget);
        static::deleted($forget);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markRead(): void
    {
        if ($this->read_at === null) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
}
