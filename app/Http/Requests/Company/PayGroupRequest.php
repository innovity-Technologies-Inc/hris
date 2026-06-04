<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class PayGroupRequest extends FormRequest
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
            'current_company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'payroll_frequency' => 'required|in:Hourly,Monthly,Weekly',
            'salary_processing_day' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'current_company_id.required' => 'Please select a company.',
            'title.required' => 'Please enter a title for the pay group.',
            'payroll_frequency.required' => 'Please select a payroll frequency.',
            'salary_processing_day.required' => 'Please select a salary processing day.',
        ];
    }
}
