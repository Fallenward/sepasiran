<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenSession extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'category', 'required_role', 'required_specialty',
        'location', 'is_online', 'preferred_date', 'preferred_slot', 'flexible_schedule',
        'duration_minutes', 'participants_needed', 'participants_filled', 'budget_min', 'budget_max',
        'extras', 'images', 'deadline_at', 'status',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'flexible_schedule' => 'boolean',
        'preferred_date' => 'date',
        'deadline_at' => 'datetime',
        'extras' => 'array',
        'images' => 'array',
        'budget_min' => 'integer',
        'budget_max' => 'integer',
        'duration_minutes' => 'integer',
        'participants_needed' => 'integer',
        'participants_filled' => 'integer',
    ];

    public const STATUSES = ['DRAFT','PUBLISHED','PAUSED','CLOSED','CANCELLED','EXPIRED','FULFILLED'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(OpenSessionApplication::class);
    }

    public function isOpenForApplications(): bool
    {
        if ((string) $this->status !== 'PUBLISHED') {
            return false;
        }
        if ($this->deadline_at && $this->deadline_at->isPast()) {
            return false;
        }

        return (int) $this->participants_filled < (int) $this->participants_needed;
    }
}