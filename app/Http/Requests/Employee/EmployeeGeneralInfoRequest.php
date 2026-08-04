<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeGeneralInfoRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('employee');
        $employee = $id ? \App\Models\Employee\Employee::find($id) : null;
        $userId = $employee ? $employee->user_id : null;
        $isEmployee = auth()->user() && auth()->user()->user_type === \App\Enums\UserType::Employee;

        $rules = [
            // System Identifiers
            'applicant_id' => 'nullable|string|unique:employees,applicant_id,' . $id,
            'system_id' => 'nullable|string|unique:employees,system_id,' . $id,
            'punch_card_no' => $isEmployee ? 'nullable|string' : 'required|string|unique:employees,punch_card_no,' . $id,

            // Personal Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'gender' => 'required|in:Male,Female,Other',
            'religion' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'height_feet' => 'nullable|integer|min:0',
            'height_inches' => 'nullable|integer|min:0|max:11',
            'children_count' => 'nullable|integer|min:0',

            'present_address.line_1' => 'required|string',
            'present_address.village' => 'nullable|string',
            'present_address.post_office' => 'required|string',
            'present_address.district' => 'required|string',
            'present_address.division' => 'required|string',
            'present_address.zip_code' => 'required|string',
            'present_address.state' => 'required|string',
            'present_address.country' => 'required|string',

            'permanent_address.line_1' => 'nullable|string',
            'permanent_address.village' => 'nullable|string',
            'permanent_address.post_office' => 'nullable|string',
            'permanent_address.district' => 'nullable|string',
            'permanent_address.division' => 'nullable|string',
            'permanent_address.zip_code' => 'nullable|string',
            'permanent_address.state' => 'nullable|string',
            'permanent_address.country' => 'nullable|string',

            'reference_address.emp_id' => 'nullable|string',
            'reference_address.reference_name' => 'nullable|string',
            'reference_address.reference_designation' => 'nullable|string',
            'reference_address.phone' => 'nullable|string',
            'reference_address.mobile' => 'nullable|string',
            'reference_address.email' => 'nullable|string',
            'reference_address.line_1' => 'nullable|string',
            'reference_address.village' => 'nullable|string',
            'reference_address.post_office' => 'nullable|string',
            'reference_address.district' => 'nullable|string',
            'reference_address.division' => 'nullable|string',
            'reference_address.zip_code' => 'nullable|string',
            'reference_address.state' => 'nullable|string',
            'reference_address.country' => 'nullable|string',

            // Document Information
            'tin' => 'nullable|string|max:255',
            'passport_no' => 'nullable|string|max:255',
            'passport_expiry' => 'nullable|date|after:today',
            'license_no' => 'nullable|string|max:255',
            'license_expiry' => 'nullable|date|after:today',
            'visa_expiry' => 'nullable|date|after:today',
            'work_expiry' => 'nullable|date|after:today',
            'residency_id_number' => 'nullable|string|max:255',
            'nid' => 'nullable|string|max:255',

            // Birth Information
            'date_of_birth' => 'required|date|before:today',
            'birth_country' => 'nullable|string|max:255',
            'birth_reg_no' => 'nullable|string|max:255',

            // Contact Information
            'personal_mobile' => 'required|string|max:20',
            'home_phone' => 'nullable|string|max:20',
            'work_mobile' => 'nullable|string|max:20',
            'work_phone' => 'nullable|string|max:20',
            'work_email' => 'nullable|email|max:255|unique:users,email,' . $userId,
            'personal_email' => 'nullable|email|max:255',

            // Login Information
            'user_type' => $id ? 'nullable|string|in:' . implode(',', \App\Enums\UserType::values()) : 'required|string|in:' . implode(',', \App\Enums\UserType::values()),
            'roles' => 'nullable|array',
            'password' => $id ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',

            // File uploads
            'photo_path' => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
            'fingerprint_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'signature_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'experience_attachment_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];

        $finalRules = app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'general');
        $finalRules['applicant_id'] = 'nullable|string|unique:employees,applicant_id,' . $id;
        $finalRules['system_id'] = 'nullable|string|unique:employees,system_id,' . $id;
        return $finalRules;
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'applicant_id.required' => 'Applicant ID is required.',
            'applicant_id.unique' => 'Applicant ID must be unique.',
            'system_id.required' => 'System ID is required.',
            'system_id.unique' => 'System ID must be unique.',
            'punch_card_no.required' => 'Punch card number is required.',
            'punch_card_no.unique' => 'Punch card number must be unique.',
            'first_name.required' => 'First name is required.',
            'father_name.required' => 'Father\'s name is required.',
            'mother_name.required' => 'Mother\'s name is required.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be Male, Female, or Other.',
            'marital_status.in' => 'Marital status must be Single, Married, Divorced, or Widowed.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'passport_expiry.after' => 'Passport expiry date must be a future date.',
            'license_expiry.after' => 'License expiry date must be a future date.',
            'visa_expiry.after' => 'Visa expiry date must be a future date.',
            'work_expiry.after' => 'Work expiry date must be a future date.',
            'personal_mobile.required' => 'Personal mobile is required.',
            'work_email.email' => 'Work email must be a valid email address.',
            'personal_email.email' => 'Personal email must be a valid email address.',
            'photo_path.image' => 'Photo must be an image file.',
            'experience_attachment_path.mimes' => 'Experience attachment must be a PDF or Word document.',
            'user_type.required' => 'User type is required for login information.',
            'password.required' => 'Password is required for new accounts.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
