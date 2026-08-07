<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GpsDevice extends Model
{
    protected $fillable = [
        'lot_id',
        'device_identifier',
        'status',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(GpsPosition::class);
    }
}