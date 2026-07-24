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
            'title' => 'required|string|max:255',
            'payroll_frequency' => 'required|in:Hourly,Monthly,Weekly,Daily',
            'working_hours_per_day' => 'required_if:payroll_frequency,Monthly,Weekly|nullable|numeric|min:1|max:24',
            'working_days_per_cycle' => 'required_if:payroll_frequency,Monthly,Weekly|nullable|numeric|min:1|max:31',
            'salary_processing_day' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please enter a title for the pay group.',
            'payroll_frequency.required' => 'Please select a payroll frequency.',
            'working_hours_per_day.required_if' => 'Working hours per day is required for Monthly/Weekly frequencies.',
            'working_days_per_cycle.required_if' => 'Working days per cycle is required for Monthly/Weekly frequencies.',
            'salary_processing_day.required' => 'Please select a salary processing day.',
        ];
    }
}
