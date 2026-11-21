<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderServicePrice extends Model
{
    protected $fillable = [
        'user_id',
        'service_type',
        'base_price',
        'training_surcharges',
    ];

    protected $casts = [
        'base_price' => 'integer',
        'training_surcharges' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function trainingSurcharge(?string $trainingType): int
    {
        if (! $trainingType) {
            return 0;
        }

        $map = $this->training_surcharges ?? [];

        return (int) ($map[$trainingType] ?? 0);
    }
}
