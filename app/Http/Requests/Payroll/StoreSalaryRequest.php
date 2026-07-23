<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Company\PayGroup;

class StoreSalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'employee_id' => 'nullable',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'pay_group_id' => 'required|exists:pay_groups,id',
        ];

        $payGroup = PayGroup::find($this->pay_group_id);
        if ($payGroup) {
            if (strtolower($payGroup->payroll_frequency) === 'monthly') {
                $rules['salary_month'] = 'required';
            } else {
                $rules['start_date'] = 'required|date';
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'salary_month.required' => 'Salary Month is required.',
            'start_date.required' => 'Start Date is required.',
            'end_date.required' => 'End Date is required.',
            'pay_group_id.required' => 'Pay Group is required.',
        ];
    }
}
