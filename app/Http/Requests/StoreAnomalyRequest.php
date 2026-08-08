<?php

namespace App\Http\Requests;

use App\Models\Anomaly;
use Illuminate\Foundation\Http\FormRequest;

class StoreAnomalyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('report', Anomaly::class);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:ecart_poids,sceau_brise,itineraire_inhabituel,document_manquant,autre'],
            'description' => ['nullable', 'string'],
            'severity' => ['required', 'in:faible,moyenne,critique'],
        ];
    }
}