<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Lot::class);
    }

    public function rules(): array
    {
        return [
            'source_id' => ['required', 'integer', 'exists:sources,id'],
            'resource_type_id' => ['required', 'integer', 'exists:resource_types,id'],
            'weight_volume' => ['required', 'numeric', 'min:0.001'],
            'weighing_mode' => ['nullable', 'string', 'max:255'],
            'extraction_date' => ['required', 'date', 'before_or_equal:today'],
            'destination' => ['nullable', 'string', 'max:255'],
            'transport_mode' => ['nullable', 'string', 'max:255'],
        ];
    }
}