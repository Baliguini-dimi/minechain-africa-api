<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('resource_type'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'unit_of_measure' => ['sometimes', 'string', 'max:50'],
            'density' => ['nullable', 'numeric'],
            'weighing_method' => ['nullable', 'string', 'max:255'],
            'quality_categories' => ['nullable', 'array'],
            'required_documents' => ['nullable', 'array'],
        ];
    }
}