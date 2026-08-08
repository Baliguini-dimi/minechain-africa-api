<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('source'));
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'in:mine_industrielle,mine_artisanale,champ_petrolier,plateforme_offshore,carriere,centrale_energetique'],
            'gps_lat' => ['sometimes', 'numeric', 'between:-90,90'],
            'gps_lng' => ['sometimes', 'numeric', 'between:-180,180'],
            'responsible_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['sometimes', 'in:active,inactive'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}