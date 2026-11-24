<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketMessage extends Model
{
    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'sender_type',
        'body',
        'attachment_path',
        'attachment_name',
        'attachment_mime',
        'attachment_size',
    ];

    protected $casts = [
        'attachment_size' => 'integer',
    ];

    protected $appends = ['attachment_url'];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        if (! $this->attachment_path) {
            return null;
        }

        return asset('storage/'.ltrim($this->attachment_path, '/'));
    }

    public function toChatArray(): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->support_ticket_id,
            'sender_type' => $this->sender_type,
            'body' => $this->body,
            'attachment_url' => $this->attachment_url,
            'attachment_name' => $this->attachment_name,
            'created_at' => optional($this->created_at)->timezone(config('app.timezone', 'Asia/Tehran'))->format('Y/m/d H:i'),
            'created_at_iso' => optional($this->created_at)?->toIso8601String(),
        ];
    }
}