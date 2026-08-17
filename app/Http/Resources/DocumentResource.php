<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'file_url' => url($this->file_url),
            'document_type' => $this->document_type,
            'uploaded_by' => $this->whenLoaded('uploader', fn () => $this->uploader->name),
            'created_at' => $this->created_at,
        ];
    }
}