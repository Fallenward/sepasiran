<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpenSessionOffer extends Model
{
    protected $fillable = [
        'application_id', 'user_id', 'amount', 'kind', 'status', 'note',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(OpenSessionApplication::class, 'application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}