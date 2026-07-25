<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxChallanRequest extends FormRequest
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
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'nullable|exists:employees,id',
            'tax_paid_from' => 'required|regex:/^\d{4}-\d{2}$/',
            'tax_paid_to' => 'required|regex:/^\d{4}-\d{2}$/|after_or_equal:tax_paid_from',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'company_id.required' => 'Please select a company.',
            'employee_id.required' => 'Please select an employee.',
            'tax_paid_from.required' => 'Start month is required.',
            'tax_paid_from.regex' => 'Start month must be in YYYY-MM format.',
            'tax_paid_to.required' => 'End month is required.',
            'tax_paid_to.regex' => 'End month must be in YYYY-MM format.',
            'tax_paid_to.after_or_equal' => 'End month must be after or equal to the start month.',
            'attachments.array' => 'Attachments must be uploaded as a list of files.',
            'attachments.*.file' => 'Each uploaded item must be a valid file.',
        ];
    }
}
