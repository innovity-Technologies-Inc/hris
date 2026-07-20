<?php

namespace App\Http\Requests\Offboarding;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOffboardingRequest extends FormRequest
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
            'employee_id' => 'sometimes|required|exists:employees,id',
            'offboarding_type' => 'sometimes|required|in:resignation,termination',
            'resignation_date' => 'sometimes|required|date',
            'notice_period_days' => 'sometimes|required|integer|min:0',
            'last_working_day' => 'sometimes|required|date|after_or_equal:resignation_date',
            'reason' => 'sometimes|required|string|max:2000',
            'remarks' => 'nullable|string|max:1000',
            'status' => 'nullable|in:pending,approved,rejected,cancelled',
        ];
    }
}
