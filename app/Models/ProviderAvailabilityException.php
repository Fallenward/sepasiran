<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAvailabilityException extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'all_day',
        'start_time',
        'end_time',
        'kind',
        'note',
    ];

    protected $casts = [
        'date' => 'date',
        'all_day' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}