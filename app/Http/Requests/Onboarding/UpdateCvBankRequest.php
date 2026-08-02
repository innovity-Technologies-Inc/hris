<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCvBankRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'career_level' => 'required|in:Entry,Mid,Senior,Executive',
            'cv_score' => 'required|integer|min:0|max:100',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }
}
