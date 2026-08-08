<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\ResourceType::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'unit_of_measure' => ['required', 'string', 'max:50'],
            'density' => ['nullable', 'numeric'],
            'weighing_method' => ['nullable', 'string', 'max:255'],
            'quality_categories' => ['nullable', 'array'],
            'required_documents' => ['nullable', 'array'],
        ];
    }
}