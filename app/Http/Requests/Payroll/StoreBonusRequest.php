<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StoreBonusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'nullable',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'pay_group_id' => 'required|exists:pay_groups,id',
            'salary_month' => 'required',
            'plan_ids' => 'required|array|min:1',
            'plan_ids.*' => 'required|integer|exists:bonus_plans,id',
        ];
    }

    public function messages(): array
    {
        return [
            'plan_ids.required' => 'Plan is required.',
            'plan_ids.*.required' => 'Plan is required.',
            'salary_month.required' => 'Salary Month is required.',
            'pay_group_id.required' => 'Pay Group is required.',
        ];
    }
}
