<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassportEvent extends Model
{
    protected $fillable = [
        'passport_id',
        'event_type',
        'actor_user_id',
        'location',
        'payload',
        'prev_hash',
        'hash',
        'signature',
        'occurred_at',
    ];

    protected $casts = [
        'location' => 'array',
        'payload' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function passport(): BelongsTo
    {
        return $this->belongsTo(Passport::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}