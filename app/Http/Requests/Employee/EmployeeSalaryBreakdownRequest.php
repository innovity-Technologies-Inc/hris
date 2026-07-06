<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeSalaryBreakdownRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->user_type !== \App\Enums\UserType::Employee;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'pay_scale_id' => 'required|exists:pay_scales,id',

            'basic_salary' => 'required|numeric|min:0',
            'house_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_earnings' => 'nullable|numeric|min:0',

            'basic_salary_percentage' => 'required|numeric|min:0',
            'house_allowance_percentage' => 'nullable|numeric|min:0',
            'transport_allowance_percentage' => 'nullable|numeric|min:0',
            'food_allowance_percentage' => 'nullable|numeric|min:0',
            'medical_allowance_percentage' => 'nullable|numeric|min:0',
            'other_earnings_percentage' => 'nullable|numeric|min:0',

            'gross_salary' => 'required|numeric|min:0',
        ];

        if ($this->has('pay_scale_id') && $this->has('gross_salary')) {
            $payScale = \App\Models\Company\PayScale::find($this->pay_scale_id);
            if ($payScale) {
                $rules['gross_salary'] .= "|numeric|min:{$payScale->min_salary}|max:{$payScale->max_salary}";
            }
        }

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'salary-breakdown');
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $totalPercentage = (float)$this->basic_salary_percentage +
                (float)$this->house_allowance_percentage +
                (float)$this->transport_allowance_percentage +
                (float)$this->food_allowance_percentage +
                (float)$this->medical_allowance_percentage +
                (float)$this->other_earnings_percentage;

            if (round($totalPercentage, 2) != 100.00) {
                $validator->errors()->add(
                    'basic_salary_percentage',
                    "The total salary breakdown percentage must be exactly 100%. Currently it is {$totalPercentage}%."
                );
            }
        });
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'The employee field is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'pay_scale_id.required' => 'Please select a Pay Scale.',
            'gross_salary.min' => 'Gross salary is lower than the minimum allowed for this pay scale.',
            'gross_salary.max' => 'Gross salary is higher than the maximum allowed for this pay scale.',

            'basic_salary.required' => 'The basic salary is required.',
            'basic_salary.numeric' => 'The basic salary must be a valid number.',
            'basic_salary.min' => 'The basic salary must be a positive value.',

            'house_allowance.numeric' => 'The house allowance must be a valid number.',
            'house_allowance.min' => 'The house allowance must be a positive value.',

            'transport_allowance.numeric' => 'The transport allowance must be a valid number.',
            'transport_allowance.min' => 'The transport allowance must be a positive value.',

            'food_allowance.numeric' => 'The food allowance must be a valid number.',
            'food_allowance.min' => 'The food allowance must be a positive value.',

            'medical_allowance.numeric' => 'The medical allowance must be a valid number.',
            'medical_allowance.min' => 'The medical allowance must be a positive value.',

            'other_earnings.numeric' => 'The other earnings must be a valid number.',
            'other_earnings.min' => 'The other earnings must be a positive value.',

            'basic_salary_percentage.required' => 'The basic salary percentage is required.',
            'basic_salary_percentage.numeric' => 'The basic salary percentage must be a valid number.',
            'basic_salary_percentage.min' => 'The basic salary percentage must be a positive value.',

            'house_allowance_percentage.numeric' => 'The house allowance percentage must be a valid number.',
            'house_allowance_percentage.min' => 'The house allowance percentage must be a positive value.',

            'transport_allowance_percentage.numeric' => 'The transport allowance percentage must be a valid number.',
            'transport_allowance_percentage.min' => 'The transport allowance percentage must be a positive value.',

            'food_allowance_percentage.numeric' => 'The food allowance percentage must be a valid number.',
            'food_allowance_percentage.min' => 'The food allowance percentage must be a positive value.',

            'medical_allowance_percentage.numeric' => 'The medical allowance percentage must be a valid number.',
            'medical_allowance_percentage.min' => 'The medical allowance percentage must be a positive value.',

            'other_earnings_percentage.numeric' => 'The other earnings percentage must be a valid number.',
            'other_earnings_percentage.min' => 'The other earnings percentage must be a positive value.',

            'gross_salary.required' => 'The gross salary is required.',
            'gross_salary.numeric' => 'The gross salary must be a valid number.',
        ];
    }
}
