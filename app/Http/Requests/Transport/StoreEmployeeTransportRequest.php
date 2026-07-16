<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeTransportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:company,branch,division,department,section',
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id|required_if:type,branch',
            'division_id' => 'nullable|exists:divisions,id|required_if:type,division',
            'department_id' => 'nullable|exists:departments,id|required_if:type,department',
            'section_id' => 'nullable|exists:sections,id|required_if:type,section',
            'service_name' => 'required|string|max:255',
            'transport_type' => 'required|in:Daily Commute,Shuttle Service,Special Transport,Field Work',
            'purpose' => 'required|string|max:1000',
            'route_map_id' => 'required|exists:route_maps,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'pickup_time' => 'nullable|date_format:H:i',
            'drop_time' => 'nullable|date_format:H:i',
            'estimated_passengers' => 'nullable|integer|min:1',
            'special_requirements' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please select a type.',
            'company_id.required' => 'Please select a company.',
            'service_name.required' => 'Service name is required.',
            'transport_type.required' => 'Please select a transport type.',
            'purpose.required' => 'Purpose is required.',
            'route_map_id.required' => 'Please select a Route Map.',
            'start_date.required' => 'Start date is required.',
        ];
    }
}
