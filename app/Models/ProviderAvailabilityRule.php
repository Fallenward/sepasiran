<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAvailabilityRule extends Model
{
    protected $fillable = [
        'user_id',
        'weekday',
        'start_time',
        'end_time',
        'is_break',
    ];

    protected $casts = [
        'weekday' => 'integer',
        'is_break' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}