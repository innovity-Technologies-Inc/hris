<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class ProfileFieldConfigSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'required_fields' => 'nullable|array',
            'required_fields.*' => 'integer|exists:profile_field_configs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'required_fields.array' => 'Invalid field configuration data.',
            'required_fields.*.integer' => 'Each field ID must be a valid integer.',
            'required_fields.*.exists' => 'One or more selected fields do not exist.',
        ];
    }
}
