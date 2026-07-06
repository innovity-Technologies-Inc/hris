<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeNomineeRequest extends FormRequest
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
            'employee_id' => 'required|integer|exists:employees,id',
            'nominee_name' => 'required|string|max:255',
            'relation' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'spouse_name' => 'nullable|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'religion' => 'nullable|string|max:100',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'nationality' => 'required|string|max:100',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nid' => 'nullable|string|max:50',
            'birth_reg_no' => 'nullable|string|max:50',
            'bank_account_no' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'required|string|max:20',
            'present_address_line' => 'nullable|string|max:255',
            'village' => 'nullable|string|max:255',
            'post_office' => 'nullable|string|max:255',
            'thana' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
        ];

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'emergency_contact');
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'Employee ID is required.',
            'employee_id.exists' => 'The selected employee does not exist.',
            'nominee_name.required' => 'Nominee name is required.',
            'gender.required' => 'Please select gender.',
            'gender.in' => 'Gender must be male, female, or other.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be a valid past date.',
            'marital_status.required' => 'Please select marital status.',
            'marital_status.in' => 'Invalid marital status value.',
            'nationality.required' => 'Nationality is required.',
            'blood_group.in' => 'Invalid blood group format.',
            'photo_path.image' => 'Photo must be an image file.',
            'photo_path.mimes' => 'Photo must be jpeg, jpg, or png format.',
            'photo_path.max' => 'Photo size must not exceed 2MB.',
            'ratio.required' => 'Nominee ratio is required.',
            'ratio.numeric' => 'Ratio must be a valid number.',
            'ratio.max' => 'Ratio cannot exceed 100%.',
            'mobile.required' => 'Mobile number is required.',
            'country.required' => 'Country field is required.',
        ];
    }
}
