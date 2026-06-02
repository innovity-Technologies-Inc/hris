<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class NotificationSettingRequest extends FormRequest
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
            'birthday_days' => 'required|integer|min:0',
            'visa_days' => 'required|integer|min:0',
            'work_permit_days' => 'required|integer|min:0',
            'passport_days' => 'required|integer|min:0',
            'license_days' => 'required|integer|min:0',
            'probation_days' => 'required|integer|min:0',
        ];
    }
}
