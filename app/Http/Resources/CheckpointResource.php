<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'gps_lat' => $this->gps_lat,
            'gps_lng' => $this->gps_lng,
            'organization_id' => $this->organization_id,
        ];
    }
}