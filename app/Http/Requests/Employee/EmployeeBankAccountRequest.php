<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeBankAccountRequest extends FormRequest
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
            'employee_id' => 'required',
            'bank_id' => 'required',
            'branch_id' => 'nullable',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ];

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'employee-bank-account');
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'bank_id.required' => 'Please select a bank.',
            'bank_id.exists' => 'The selected bank does not exist.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'account_holder_name.required' => 'Account holder name is required.',
            'account_holder_name.max' => 'Account holder name cannot exceed 255 characters.',
            'account_number.required' => 'Account number is required.',
            'account_number.max' => 'Account number cannot exceed 255 characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Status must be either active or inactive.',
        ];
    }
}
