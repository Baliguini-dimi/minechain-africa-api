<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckpointControl extends Model
{
    protected $fillable = [
        'checkpoint_id',
        'lot_id',
        'agent_user_id',
        'control_datetime',
        'status',
        'observations',
    ];

    protected $casts = [
        'control_datetime' => 'datetime',
    ];

    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_user_id');
    }
}