<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveAnomalyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('resolve', $this->route('anomaly'));
    }

    public function rules(): array
    {
        return [
            'resolution' => ['required', 'in:resolved,dismissed'],
        ];
    }
}