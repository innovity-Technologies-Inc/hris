<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'section' => 'required|string|in:general,education,employment_history,emergency_contact',
            'requested_data' => 'required|array',
            'previous_data' => 'nullable|array',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'Employee is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'section.required' => 'Profile section is required.',
            'section.in' => 'Invalid profile section.',
            'requested_data.required' => 'Requested data is required.',
            'requested_data.array' => 'Requested data must be an array.',
        ];
    }
}
