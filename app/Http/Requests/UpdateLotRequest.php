<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('lot'));
    }

    public function rules(): array
    {
        return [
            'destination' => ['sometimes', 'string', 'max:255'],
            'transport_mode' => ['sometimes', 'string', 'max:255'],
        ];
    }
}