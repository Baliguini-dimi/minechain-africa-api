<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('organization'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'country' => ['sometimes', 'string', 'max:2'],
            'address' => ['nullable', 'string'],
            'contacts' => ['nullable', 'array'],
            'logo_url' => ['nullable', 'url'],
            'status' => ['sometimes', 'in:active,suspended,pending_validation'],
        ];
    }
}