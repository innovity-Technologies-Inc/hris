<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('transfers.create');
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'requested_company_id' => 'required|exists:companies,id',
            'requested_business_unit_id' => 'nullable|exists:company_locations,id',
            'requested_division_id' => 'nullable|exists:divisions,id',
            'requested_department_id' => 'nullable|exists:departments,id',
            'requested_section_id' => 'nullable|exists:sections,id',
            'requested_designation_id' => 'nullable|exists:designations,id',
            'remarks' => 'nullable|string|max:500',
        ];
    }
}
