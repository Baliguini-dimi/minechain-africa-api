<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GpsPosition extends Model
{
    protected $fillable = [
        'gps_device_id',
        'lot_id',
        'lat',
        'lng',
        'speed',
        'recorded_at',
        'is_anomaly_flagged',
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'speed' => 'decimal:2',
        'recorded_at' => 'datetime',
        'is_anomaly_flagged' => 'boolean',
    ];

    public function gpsDevice(): BelongsTo
    {
        return $this->belongsTo(GpsDevice::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}