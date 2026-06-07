<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class SalaryGradeRequest extends FormRequest
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
            'grade_code' => 'required|string|max:50',
            'grade_name' => 'required|string|max:255',
            'tofsil_id' => 'required|exists:tofsils,id',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'grade_code.required' => 'Please enter grade code.',
            'grade_name.required' => 'Please enter grade name.',
            'tofsil_id.required' => 'Please select a tofsil.',
        ];
    }
}
