<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'role' => $this->whenLoaded('role', fn () => $this->role->name),
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'two_factor_enabled' => $this->two_factor_enabled,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}