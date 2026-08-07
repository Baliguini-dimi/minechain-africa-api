<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Passport extends Model
{
    protected $fillable = [
        'lot_id',
        'unique_identifier',
        'status',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PassportEvent::class)->orderBy('occurred_at');
    }
}