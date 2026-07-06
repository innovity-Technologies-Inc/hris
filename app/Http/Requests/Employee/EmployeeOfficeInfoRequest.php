<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeOfficeInfoRequest extends FormRequest
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
            // Basic Identifiers
            'employee_id' => 'required|integer',
            'emp_type' => 'nullable|in:permanent,contractual',
            'grade_id' => 'nullable|integer',
            'hr_file_no' => 'nullable|string|max:255',
            'file_note' => 'nullable|string',

            // Joining Information
            'joining_company_id' => 'nullable|integer',
            'joining_business_unit_id' => 'nullable|integer',
            'joining_division_id' => 'nullable|integer',
            'joining_department_id' => 'nullable|integer',
            'joining_section_id' => 'nullable|integer',
            'joining_designation_id' => 'nullable|integer',
            'date_of_join' => 'nullable|date',

            // Current Posting Information
            'current_company_id' => 'nullable|integer',
            'current_business_unit_id' => 'nullable|integer',
            'current_division_id' => 'nullable|integer',
            'current_department_id' => 'nullable|integer',
            'current_section_id' => 'nullable|integer',
            'current_designation_id' => 'nullable|integer',

            // Orientation
            'orientation_required' => 'required|in:yes,no',
            'orientation_from' => 'nullable|date',
            'orientation_to' => 'nullable|date|after_or_equal:orientation_from',
            'orientation_type' => 'nullable|string|max:100',
            'orientation_days' => 'nullable|integer|min:1',

            // Employment & Performance
            'confirmation_date' => 'nullable|date',
            'probation_duration' => 'nullable|integer|min:0',
            'next_promotion_date' => 'nullable|date',
            'promotion_cycle' => 'nullable|string|max:100',
            'increment_cycle' => 'nullable|string|max:100',

            // Attendance & Benefits
            'weekends' => 'nullable|array',
            'weekends.*' => 'string',
            'alternate_off_day' => 'nullable|array',
            'alternate_off_day.*' => 'string',
            'ot_allowed' => 'nullable|in:yes,no',
            'pf_eligible' => 'nullable|in:yes,no',
            'transport_eligible' => 'nullable|in:yes,no',

            // Loan & Benefits
            'can_apply_loan' => 'nullable|in:yes,no',
            'pf_effective_date' => 'nullable|date',
            'can_apply_advance' => 'nullable|in:yes,no',
            'gratuity_eligible' => 'nullable|in:yes,no',
        ];

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'office-information');
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'emp_type.in' => 'Employment type must be either Permanent or Contractual.',
            'orientation_to.after_or_equal' => 'Orientation end date must be after or equal to the start date.',
            'orientation_days.min' => 'Orientation days must be at least 1 day.',
            'probation_duration.min' => 'Probation duration cannot be negative.',
        ];
    }
}
