<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'pay_scale_id' => 'nullable|exists:pay_scales,id',
            'movement_type_id' => 'nullable|exists:movement_types,id',
            'previous_designation' => 'required|exists:designations,id',
            'new_designation' => 'required|exists:designations,id|different:previous_designation',
            'increment_base' => 'required|in:basic_salary,gross_salary',
            'increment_method' => 'required|in:fixed,percentage',
            'salary_increase_amount' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'status' => 'nullable|in:approved,pending,rejected',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please Select An Employee',
            'employee_id.exists' => 'Selected Employee Is Invalid',
            'pay_scale_id.exists' => 'Selected Pay Scale Is Invalid',
            'movement_type_id.exists' => 'Selected Movement Type Is Invalid',
            'previous_designation.required' => 'Please Select Previous Designation',
            'previous_designation.exists' => 'Previous Designation Is Invalid',
            'new_designation.required' => 'Please Select New Designation',
            'new_designation.exists' => 'New Designation Is Invalid',
            'new_designation.different' => 'New Designation Must Be Different From Previous Designation',
            'increment_base.required' => 'Please Select Increment Base',
            'increment_base.in' => 'Selected Increment Base Is Invalid',
            'increment_method.required' => 'Please Select Increment Method',
            'increment_method.in' => 'Selected Increment Method Is Invalid',
            'salary_increase_amount.required' => 'Please Enter Salary Increase Amount',
            'salary_increase_amount.numeric' => 'Increase Amount Must Be A Number',
            'effective_from.required' => 'Please Select Effective Date',
            'effective_from.date' => 'Please Enter A Valid Date',
            'effective_to.date' => 'Please Enter A Valid Date',
            'effective_to.after_or_equal' => 'Effective To Date Must Be After Or Equal To Effective From Date',
        ];
    }
}
