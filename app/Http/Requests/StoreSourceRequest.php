<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Source::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:mine_industrielle,mine_artisanale,champ_petrolier,plateforme_offshore,carriere,centrale_energetique'],
            'gps_lat' => ['required', 'numeric', 'between:-90,90'],
            'gps_lng' => ['required', 'numeric', 'between:-180,180'],
            'responsible_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'capacity' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}