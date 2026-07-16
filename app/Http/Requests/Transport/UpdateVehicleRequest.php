<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_category' => 'required|in:Car,Bus,Micro Bus,Truck,Bike,Van,Airplane,Ship',
            'model_number' => 'required|string|max:255',
            'manufacture_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'body_type' => 'nullable|string|max:255',
            'fuel_type' => 'required|in:Petrol,Diesel,CNG,Electric',
            'engine_capacity' => 'nullable|string|max:50',
            'seating_capacity' => 'nullable|integer|min:1|max:500',
            'color' => 'nullable|string|max:100',
            'mileage' => 'nullable|numeric|min:0',
            'license_number' => 'nullable|string|max:100',
            'license_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'vehicle_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'purchase_type' => 'required|in:Purchase,Lease,Rent',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ownership_type' => 'required|in:Company-owned,Third-party',
            'third_party_name' => 'nullable|required_if:ownership_type,Third-party|string|max:255',
            'is_allocated' => 'nullable|boolean',
            'allocation_purpose' => 'nullable|string|max:255',
            'allocation_type' => 'nullable|in:trip,transport',
            'status' => 'required|in:Active,Inactive',
        ];
    }
}
