<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAvailabilitySetting extends Model
{
    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'min_notice_minutes',
        'max_advance_days',
        'slot_interval_minutes',
    ];

    protected $casts = [
        'min_notice_minutes' => 'integer',
        'max_advance_days' => 'integer',
        'slot_interval_minutes' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}