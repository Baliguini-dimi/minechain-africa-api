<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->whenLoaded('user', fn () => $this->user->name),
            'action' => $this->action,
            'entity_type' => class_basename($this->entity_type),
            'entity_id' => $this->entity_id,
            'old_value' => $this->old_value,
            'new_value' => $this->new_value,
            'occurred_at' => $this->occurred_at,
        ];
    }
}