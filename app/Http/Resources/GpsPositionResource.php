<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GpsPositionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lot_id' => $this->lot_id,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'speed' => $this->speed,
            'recorded_at' => $this->recorded_at,
            'is_anomaly_flagged' => $this->is_anomaly_flagged,
        ];
    }
}