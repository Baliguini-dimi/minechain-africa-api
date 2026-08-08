<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'name' => $this->name,
            'type' => $this->type,
            'gps_lat' => $this->gps_lat,
            'gps_lng' => $this->gps_lng,
            'responsible_user_id' => $this->responsible_user_id,
            'status' => $this->status,
            'capacity' => $this->capacity,
            'created_at' => $this->created_at,
        ];
    }
}