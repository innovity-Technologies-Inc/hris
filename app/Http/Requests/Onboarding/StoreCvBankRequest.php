<?php

namespace App\Http\Requests\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreCvBankRequest extends FormRequest
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
            'cvs' => 'required|array|min:1',
            'cvs.*.company_name' => 'required|string|max:255',
            'cvs.*.designation' => 'required|string|max:255',
            'cvs.*.applicant_name' => 'required|string|max:255',
            'cvs.*.career_level' => 'required|in:Entry,Mid,Senior,Executive',
            'cvs.*.cv_score' => 'required|integer|min:0|max:100',
            'cvs.*.attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cvs.*.company_name' => 'company name',
            'cvs.*.designation' => 'designation',
            'cvs.*.applicant_name' => 'applicant name',
            'cvs.*.career_level' => 'career level',
            'cvs.*.cv_score' => 'CV score',
            'cvs.*.attachment' => 'CV attachment',
        ];
    }
}
