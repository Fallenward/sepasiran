<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DogOrder extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'dog_id',
        'buyer_id',
        'seller_id',
        'price',
        'status',
        'payment_method',
        'gateway_driver',
        'gateway_transaction_id',
        'transaction_ref',
        'paid_at',
    ];

    protected $casts = [
        'price' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}