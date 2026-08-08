<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Organization::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:2'],
            'address' => ['nullable', 'string'],
            'contacts' => ['nullable', 'array'],
            'logo_url' => ['nullable', 'url'],
        ];
    }
}