<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'organization_id' => $this->organization_id,
            'source' => $this->whenLoaded('source', fn () => [
                'id' => $this->source->id,
                'name' => $this->source->name,
            ]),
            'resource_type' => $this->whenLoaded('resourceType', fn () => [
                'id' => $this->resourceType->id,
                'name' => $this->resourceType->name,
                'unit_of_measure' => $this->resourceType->unit_of_measure,
            ]),
            'weight_volume' => $this->weight_volume,
            'weighing_mode' => $this->weighing_mode,
            'extraction_date' => $this->extraction_date,
            'creation_date' => $this->creation_date,
            'departure_date' => $this->departure_date,
            'destination' => $this->destination,
            'transport_mode' => $this->transport_mode,
            'status' => $this->status,
            'passport' => $this->whenLoaded('passport', fn () => [
                'id' => $this->passport->id,
                'unique_identifier' => $this->passport->unique_identifier,
                'status' => $this->passport->status,
                'events' => $this->passport->relationLoaded('events')
                    ? $this->passport->events->map(fn ($event) => [
                        'event_type' => $event->event_type,
                        'occurred_at' => $event->occurred_at,
                        'hash' => $event->hash,
                        'prev_hash' => $event->prev_hash,
                    ])
                    : null,
            ]),
            'qr_code' => $this->whenLoaded('qrCode', fn () => [
                'code_value' => $this->qrCode->code_value,
                'generated_at' => $this->qrCode->generated_at,
            ]),
        ];
    }
}