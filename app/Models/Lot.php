<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Lot extends Model
{
    protected $fillable = [
        'uuid',
        'organization_id',
        'source_id',
        'resource_type_id',
        'weight_volume',
        'weighing_mode',
        'extraction_date',
        'creation_date',
        'departure_date',
        'destination',
        'transport_mode',
        'responsible_user_id',
        'status',
    ];

    protected $casts = [
        'weight_volume' => 'decimal:3',
        'extraction_date' => 'date',
        'creation_date' => 'datetime',
        'departure_date' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lot $lot) {
            if (empty($lot->uuid)) {
                $lot->uuid = (string) Str::uuid();
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_user_id');
    }

    public function passport(): HasOne
    {
        return $this->hasOne(Passport::class);
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class);
    }

    public function gpsPositions(): HasMany
    {
        return $this->hasMany(GpsPosition::class);
    }

    public function checkpointControls(): HasMany
    {
        return $this->hasMany(CheckpointControl::class);
    }

    public function anomalies(): HasMany
    {
        return $this->hasMany(Anomaly::class);
    }
}