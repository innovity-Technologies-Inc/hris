<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class DecrementRequest extends FormRequest
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
            'decrement_base' => 'required|in:basic_salary,gross_salary',
            'decrement_method' => 'required|in:fixed,percentage',
            'salary_decrease_amount' => 'required|numeric|min:0',
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
            'decrement_base.required' => 'Please Select Decrement Base',
            'decrement_base.in' => 'Selected Decrement Base Is Invalid',
            'decrement_method.required' => 'Please Select Decrement Method',
            'decrement_method.in' => 'Selected Decrement Method Is Invalid',
            'salary_decrease_amount.required' => 'Please Enter Salary Decrease Amount',
            'salary_decrease_amount.numeric' => 'Decrease Amount Must Be A Number',
            'effective_from.required' => 'Please Select Effective Date',
            'effective_from.date' => 'Please Enter A Valid Date',
            'effective_to.date' => 'Please Enter A Valid Date',
            'effective_to.after_or_equal' => 'Effective To Date Must Be After Or Equal To Effective From Date',
        ];
    }
}
