<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePlanAssignmentRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $type = $this->route('type');

        if (in_array($type, ['bonus-plans', 'leave-plans'])) {
            return [
                'employee_id' => 'required|exists:employees,id',
                'plan_ids' => 'required|array|min:1',
                'plan_ids.*' => 'required',
            ];
        }

        return [
            'employee_id' => 'required|exists:employees,id',
            'plan_id' => 'required',
            'from' => 'required|date',
            'to' => 'nullable|date|after_or_equal:from',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'The employee field is required.',
            'employee_id.exists' => 'The selected employee is invalid.',
            'plan_id.required' => 'The plan field is required.',
            'plan_ids.required' => 'Please select at least one plan.',
            'from.required' => 'The from date field is required.',
            'to.after_or_equal' => 'The to date must be after or equal to the from date.',
        ];
    }
}
