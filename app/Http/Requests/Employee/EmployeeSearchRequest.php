<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserType;

class EmployeeSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->user_type !== UserType::Employee;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'keyword' => 'nullable|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
            'system_id' => 'nullable|string|max:255',
            'company' => 'nullable|integer',
            'business_unit' => 'nullable|integer',
            'division' => 'nullable|integer',
            'department' => 'nullable|integer',
            'section' => 'nullable|integer',
            'emp_type' => 'nullable|string|in:permanent,contractual',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'marital_status' => 'nullable|string|in:Single,Married,Divorced,Widowed',
            'age_from' => 'nullable|integer|min:0',
            'age_to' => 'nullable|integer|min:0',
            'religion' => 'nullable|string|max:255',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'nationality' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ];
    }
}
