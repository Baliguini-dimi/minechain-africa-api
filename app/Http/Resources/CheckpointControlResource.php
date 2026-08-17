<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckpointControlResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'checkpoint_id' => $this->checkpoint_id,
            'lot_id' => $this->lot_id,
            'agent_user_id' => $this->agent_user_id,
            'control_datetime' => $this->control_datetime,
            'status' => $this->status,
            'measured_weight' => $this->measured_weight,
            'observations' => $this->observations,
        ];
    }
}