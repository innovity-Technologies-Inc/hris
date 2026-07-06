<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Employee\EmployeeServices;

class EmployeeEducationRequest extends FormRequest
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
            'employee_id' => 'required',
            'educations' => 'nullable|array',
            'educations.*.education_title' => 'nullable|string|max:255',
            'educations.*.institute' => 'nullable|string|max:255',
            'educations.*.group_major' => 'nullable|string|max:255',
            'educations.*.board_university' => 'nullable|string|max:255',
            'educations.*.result_grade' => 'nullable|string|max:100',
            'educations.*.passing_year' => 'nullable|string|max:10',
            'educations.*.gpa_cgpa' => 'nullable|string|max:20',
            'trainings' => 'nullable|array',
            'trainings.*.training_title' => 'nullable|string|max:255',

            'trainings.*.course_name' => 'nullable|string|max:255',
            'trainings.*.training_code' => 'nullable|string|max:100',
            'trainings.*.institute' => 'nullable|string|max:255',
            'trainings.*.country' => 'nullable|string|max:100',
            'trainings.*.location' => 'nullable|string|max:255',
            'trainings.*.duration' => 'nullable|string|max:100',
            'trainings.*.from_date' => 'nullable|date',
            'trainings.*.to_date' => 'nullable|date|after_or_equal:trainings.*.from_date',
        ];

        return app(EmployeeServices::class)->getProfileFieldConfigRules($rules, 'education');
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Employee ID
            'employee_id.required' => 'Employee is required.',

            // Education fields
            'educations.*.education_title.string' => 'Education title must be a valid text.',
            'educations.*.education_title.max' => 'Education title cannot exceed 255 characters.',
            'educations.*.institute.string' => 'Institute name must be a valid text.',
            'educations.*.institute.max' => 'Institute name cannot exceed 255 characters.',
            'educations.*.group_major.string' => 'Group/Major must be a valid text.',
            'educations.*.group_major.max' => 'Group/Major cannot exceed 255 characters.',
            'educations.*.board_university.string' => 'Board/University must be a valid text.',
            'educations.*.board_university.max' => 'Board/University cannot exceed 255 characters.',
            'educations.*.result_grade.string' => 'Result/Grade must be a valid text.',
            'educations.*.result_grade.max' => 'Result/Grade cannot exceed 100 characters.',
            'educations.*.passing_year.string' => 'Passing year must be a valid text.',
            'educations.*.passing_year.max' => 'Passing year cannot exceed 10 characters.',
            'educations.*.gpa_cgpa.string' => 'GPA/CGPA must be a valid text.',
            'educations.*.gpa_cgpa.max' => 'GPA/CGPA cannot exceed 20 characters.',

            // Training fields
            'trainings.*.training_title.string' => 'Training title must be a valid text.',
            'trainings.*.training_title.max' => 'Training title cannot exceed 255 characters.',
            'trainings.*.course_name.string' => 'Course name must be a valid text.',
            'trainings.*.course_name.max' => 'Course name cannot exceed 255 characters.',
            'trainings.*.training_code.string' => 'Training code must be a valid text.',
            'trainings.*.training_code.max' => 'Training code cannot exceed 100 characters.',
            'trainings.*.institute.string' => 'Institute must be a valid text.',
            'trainings.*.institute.max' => 'Institute cannot exceed 255 characters.',
            'trainings.*.country.string' => 'Country must be a valid text.',
            'trainings.*.country.max' => 'Country cannot exceed 100 characters.',
            'trainings.*.location.string' => 'Location must be a valid text.',
            'trainings.*.location.max' => 'Location cannot exceed 255 characters.',
            'trainings.*.duration.string' => 'Duration must be a valid text.',
            'trainings.*.duration.max' => 'Duration cannot exceed 100 characters.',
            'trainings.*.from_date.date' => 'From date must be a valid date.',
            'trainings.*.to_date.date' => 'To date must be a valid date.',
            'trainings.*.to_date.after_or_equal' => 'To date must be after or equal to from date.',
        ];
    }
}
