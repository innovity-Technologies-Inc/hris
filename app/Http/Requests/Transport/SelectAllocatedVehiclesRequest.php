<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class SelectAllocatedVehiclesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_ids' => 'required|array|min:1',
            'vehicle_ids.*' => 'exists:vehicles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_ids.required' => 'Please select at least one vehicle.',
            'vehicle_ids.min' => 'Please select at least one vehicle.',
        ];
    }
}
