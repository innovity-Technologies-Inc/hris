<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'allocation_type' => 'required|string',
            'vehicle_ids' => 'required|array|min:1',
            'vehicle_ids.*' => 'exists:vehicles,id',
            'name' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|integer',
            'route_start' => 'nullable|string|max:255',
            'route_end' => 'nullable|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
            'departure_time' => 'nullable|date_format:H:i',
            'arrival_time' => 'nullable|date_format:H:i',
            'route_description' => 'nullable|string',
            'special_instructions' => 'nullable|string',
            'remarks' => 'nullable|string|max:1000',
        ];
    }
}
