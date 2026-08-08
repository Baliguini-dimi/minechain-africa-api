<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignGpsDeviceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('record', $this->route('lot'));
    }

    public function rules(): array
    {
        return [
            'device_identifier' => ['required', 'string'],
        ];
    }
}