<?php

namespace App\Http\Requests\Structure;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeyPersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'type' => 'required|in:group,company,location,division,department,section',
            'employee_id' => 'nullable|exists:employees,id',
            'name' => 'required_without:employee_id|nullable|string|max:150',
            'position' => 'required|string|max:100',
            'email' => 'nullable|email|max:150|unique:organization_structure,email,' . $id,
            'contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Hierarchy inputs
            'group_id' => 'required|exists:groups,id',
            'company_id' => 'nullable|exists:companies,id',
            'branch_unit_id' => 'required_if:type,location|nullable|exists:company_locations,id',
            'division_id' => 'required_if:type,division,department,section|nullable|exists:divisions,id',
            'department_id' => 'required_if:type,department,section|nullable|exists:departments,id',
            'section_id' => 'required_if:type,section|nullable|exists:sections,id',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'The type field is required.',
            'type.in' => 'The selected type is invalid.',
            'employee_id.exists' => 'The selected employee is invalid.',
            'name.required_without' => 'Name is required when not attaching an employee.',
            'position.required' => 'Position/Designation is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'photo_path.image' => 'The file must be an image.',
            'photo_path.max' => 'The image size may not exceed 2MB.',
            
            'group_id.required' => 'The group field is required.',
            'company_id.required_if' => 'The company field is required.',
            'branch_unit_id.required_if' => 'The branch field is required.',
            'division_id.required_if' => 'The division field is required.',
            'department_id.required_if' => 'The department field is required.',
            'section_id.required_if' => 'The section field is required.',
        ];
    }
}
