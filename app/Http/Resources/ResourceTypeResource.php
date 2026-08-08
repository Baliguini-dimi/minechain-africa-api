<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'name' => $this->name,
            'unit_of_measure' => $this->unit_of_measure,
            'density' => $this->density,
            'weighing_method' => $this->weighing_method,
            'quality_categories' => $this->quality_categories,
            'required_documents' => $this->required_documents,
            'is_global' => is_null($this->organization_id),
        ];
    }
}