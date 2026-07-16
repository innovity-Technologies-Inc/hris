<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequisitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'nullable|exists:employees,id',
            'department' => 'nullable|exists:departments,id',
            'trip_type' => 'required|in:Official,Personal,Visitor',
            'trip_mode' => 'required|in:One-way,Round-trip,Multi-stop',
            'purpose_of_travel' => 'required|string|max:1000',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after_or_equal:start_date_time',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'no_of_passengers' => 'required|integer|min:1|max:100',
            'vehicle_type_required' => 'required|in:Car,Bus,Micro',
            'driver_required' => 'nullable|boolean',
            'self_drive' => 'nullable|boolean',
            'special_requirement' => 'nullable|string|max:500',
            'preferred_vehicle' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'purpose_of_travel.required' => 'Purpose of travel is mandatory.',
            'start_date_time.required' => 'Start date and time is required.',
            'end_date_time.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }
}
