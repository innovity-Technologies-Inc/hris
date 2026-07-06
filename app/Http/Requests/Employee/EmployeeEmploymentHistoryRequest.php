<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeEmploymentHistoryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'histories' => 'required|array|min:1',
            'histories.*.company_name' => 'required|string|max:255',
            'histories.*.designation' => 'required|string|max:255',
            'histories.*.joining_date' => 'required|date',
            'histories.*.end_date' => 'nullable|date|after_or_equal:histories.*.joining_date',
            'histories.*.job_description' => 'nullable|string',
            'histories.*.achievements' => 'nullable|string',
        ];

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'employment_history');
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [];
    }
}
