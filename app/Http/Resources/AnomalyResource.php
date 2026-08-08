<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnomalyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'lot_id' => $this->lot_id,
            'type' => $this->type,
            'description' => $this->description,
            'severity' => $this->severity,
            'detected_by' => $this->detected_by,
            'status' => $this->status,
            'reported_by_user_id' => $this->reported_by_user_id,
            'resolved_by_user_id' => $this->resolved_by_user_id,
            'resolved_at' => $this->resolved_at,
            'created_at' => $this->created_at,
        ];
    }
}