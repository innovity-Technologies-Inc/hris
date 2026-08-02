<?php

namespace App\Http\Requests\Movement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'from_date' => ['required', 'date'],
            'to_date'   => ['required', 'date', 'after_or_equal:from_date'],
            'distance' => ['required', 'numeric', 'min:0'],
            'total_days' => ['required', 'numeric'],
            'status' => ['required', 'in:pending,approved,rejected'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable'],
            'items.*.source_address' => ['required', 'string', 'max:255'],
            'items.*.source_lat' => ['required', 'numeric', 'between:-90,90'],
            'items.*.source_lng' => ['required', 'numeric', 'between:-180,180'],
            'items.*.destination_address' => ['required', 'string', 'max:255'],
            'items.*.dest_lat' => ['required', 'numeric', 'between:-90,90'],
            'items.*.dest_lng' => ['required', 'numeric', 'between:-180,180'],
            'items.*.distance' => ['required', 'numeric', 'min:0'],
            'items.*.reason' => ['required', 'string', 'max:1000'],
            'items.*.attachment' => ['nullable', 'file', 'max:5120'], // 5MB limit
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'from_date.required' => 'From date and time is required.',
            'to_date.required' => 'To date and time is required.',
            'to_date.after_or_equal' => 'To date must be later than or equal to From date.',
            'distance.required' => 'Total distance must be calculated before submitting.',
            'total_days.required' => 'Total days must be calculated before submitting.',
            'items.required' => 'At least one travel route/destination card is required.',
            'items.*.source_address.required' => 'Source address is required for all routes.',
            'items.*.destination_address.required' => 'Destination address is required for all routes.',
            'items.*.distance.required' => 'Distance must be calculated for all routes.',
            'items.*.reason.required' => 'Reason is required for all routes.',
        ];
    }
}
