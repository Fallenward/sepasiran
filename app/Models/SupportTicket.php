<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'subject',
        'status',
        'unread_for_admin',
        'unread_for_user',
        'last_message_at',
    ];

    protected $casts = [
        'unread_for_admin' => 'integer',
        'unread_for_user' => 'integer',
        'last_message_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class)->orderBy('id');
    }

    public function isOpen(): bool
    {
        return ! in_array($this->status, ['closed'], true);
    }
}