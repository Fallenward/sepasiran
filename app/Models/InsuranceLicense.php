<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceLicense extends Model
{
    protected $fillable = [
        'license_code',
        'user_id',
        'insurance_id',
        'insurance_plan_id',
        'dog_id',
        'starts_at',
        'ends_at',
        'status',
        'amount_paid',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount_paid' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InsurancePlan::class, 'insurance_plan_id');
    }

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive(Builder $query): Builder
    {
        $tz = config('app.timezone', 'Asia/Tehran');

        return $query->where('status', 'active')
            ->where('ends_at', '>=', Carbon::now($tz));
    }

    public function isCurrentlyActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $tz = config('app.timezone', 'Asia/Tehran');

        return $this->ends_at && $this->ends_at->greaterThanOrEqualTo(Carbon::now($tz));
    }

    public function refreshStatus(): void
    {
        if ($this->status === 'cancelled') {
            return;
        }

        $next = $this->isCurrentlyActive() ? 'active' : 'expired';
        if ($this->status !== $next) {
            $this->forceFill(['status' => $next])->save();
        }
    }
}
