<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class PayScaleRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'grade_id' => 'required|exists:salary_grades,id',
            'pay_group_id' => 'required|exists:pay_groups,id',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'required|numeric|gt:min_salary',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'grade_id.required' => 'Please select a salary grade.',
            'pay_group_id.required' => 'Please select a pay group.',
            'min_salary.required' => 'Please enter minimum salary.',
            'max_salary.required' => 'Please enter maximum salary.',
            'max_salary.gt' => 'Maximum salary must be greater than minimum salary.',
        ];
    }
}
