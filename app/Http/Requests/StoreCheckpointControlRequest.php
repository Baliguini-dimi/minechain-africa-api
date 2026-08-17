<?php

namespace App\Http\Requests;

use App\Models\Checkpoint;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckpointControlRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('registerControl', Checkpoint::class);
    }

    public function rules(): array
    {
        return [
            'qr_token' => ['required', 'string'],
            'checkpoint_id' => ['required', 'integer', 'exists:checkpoints,id'],
            'status' => ['required', 'in:ok,anomaly_reported'],
            'measured_weight' => ['nullable', 'numeric', 'min:0'],
            'observations' => ['nullable', 'string'],
        ];
    }
}