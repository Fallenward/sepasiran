<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenSessionApplication extends Model
{
    protected $fillable = [
        'open_session_id', 'user_id', 'dog_id', 'intro', 'experience',
        'proposed_price', 'availability', 'message', 'status',
    ];

    protected $casts = [
        'proposed_price' => 'integer',
    ];

    public const STATUSES = ['PENDING','NEGOTIATING','ACCEPTED','REJECTED','WITHDRAWN','EXPIRED'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(OpenSession::class, 'open_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dog(): BelongsTo
    {
        return $this->belongsTo(Dog::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(OpenSessionOffer::class, 'application_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(OpenSessionMessage::class, 'application_id');
    }
}