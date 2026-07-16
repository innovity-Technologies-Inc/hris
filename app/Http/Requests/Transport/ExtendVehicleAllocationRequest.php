<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class ExtendVehicleAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'new_end_date' => 'required|date|after:today',
            'extension_remarks' => 'nullable|string|max:500',
        ];
    }
}
