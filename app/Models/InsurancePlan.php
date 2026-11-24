<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsurancePlan extends Model
{
    protected $fillable = [
        'insurance_id',
        'name',
        'duration_months',
        'price',
        'coverage_summary',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'duration_months' => 'integer',
        'price' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    public function licenses(): HasMany
    {
        return $this->hasMany(InsuranceLicense::class);
    }
}
