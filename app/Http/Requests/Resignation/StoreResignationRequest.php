<?php

namespace App\Http\Requests\Resignation;

use Illuminate\Foundation\Http\FormRequest;

class StoreResignationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'employee_id' => 'required|exists:employees,id',
            'resignation_date' => 'required|date',
            'notice_period_days' => 'required|integer|min:0',
            'last_working_day' => 'required|date|after_or_equal:resignation_date',
            'reason' => 'required|string|max:2000',
            'remarks' => 'nullable|string|max:1000',
        ];
    }
}
