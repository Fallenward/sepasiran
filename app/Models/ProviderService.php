<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderService extends Model
{
    protected $fillable = [
        'user_id',
        'service_type',
        'name',
        'description',
        'specialty_id',
        'base_price',
        'currency',
        'duration_minutes',
        'training_surcharges',
        'extras',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'integer',
        'duration_minutes' => 'integer',
        'training_surcharges' => 'array',
        'extras' => 'array',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_service_id');
    }

    public function trainingSurcharge(?string $trainingType): int
    {
        if (! $trainingType) {
            return 0;
        }
        $map = $this->training_surcharges ?? [];
        if (array_key_exists($trainingType, $map)) {
            return (int) $map[$trainingType];
        }

        return \App\Support\BookingCatalog::trainingSurcharge($trainingType);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}