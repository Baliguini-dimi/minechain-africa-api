<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordGpsPositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('record', $this->route('lot'));
    }

    public function rules(): array
    {
        return [
            'device_identifier' => ['sometimes', 'string'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'speed' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}