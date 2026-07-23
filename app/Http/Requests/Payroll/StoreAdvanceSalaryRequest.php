<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Company\PayGroup;

class StoreAdvanceSalaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $rules = [
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
            'pay_group_id' => 'required|exists:pay_groups,id',
            'employee_id' => 'nullable|exists:employees,id',
            'deduction_month' => 'required',
            'amount_type' => 'required|in:fixed,percentage',
            'amount_value' => 'required|numeric|min:0.01',
            'percentage_base' => 'required_if:amount_type,percentage|in:basic_salary,gross_salary',
            'reason' => 'nullable|string',
        ];

        $payGroup = PayGroup::find($this->pay_group_id);
        if ($payGroup) {
            $frequency = strtolower($payGroup->payroll_frequency);
            if ($frequency === 'monthly') {
                $rules['salary_month'] = 'required';
            } else {
                $rules['start_date'] = 'required|date';
                $rules['end_date'] = 'required|date|after_or_equal:start_date';
            }
        }

        return $rules;
    }
}
