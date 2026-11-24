<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenSessionMessage extends Model
{
    protected $fillable = [
        'application_id', 'user_id', 'type', 'body', 'offer_id',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(OpenSessionApplication::class, 'application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(OpenSessionOffer::class, 'offer_id');
    }
}