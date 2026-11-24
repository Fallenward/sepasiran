<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insurance extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'contact_phone',
        'contact_email',
        'website',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function dogs(): HasMany
    {
        return $this->hasMany(Dog::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(InsurancePlan::class)->orderBy('sort_order')->orderBy('duration_months');
    }

    public function activePlans(): HasMany
    {
        return $this->plans()->where('is_active', true);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(InsuranceLicense::class);
    }

    public function logoUrl(): string
    {
        $raw = $this->logo;
        if (! $raw) {
            return asset('images/demo/hero/hero-03.jpg');
        }
        if (str_starts_with($raw, 'http')) {
            return $raw;
        }
        if (str_starts_with($raw, 'images/')) {
            return asset($raw);
        }

        return asset('storage/'.ltrim($raw, '/'));
    }
}

