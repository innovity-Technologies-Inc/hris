<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeavePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:100',
            'applicable_gender' => 'required|in:Both,Male,Female',
            'leave_type' => 'required|string',
            'leave_limit' => 'nullable|integer|min:0',
            'max_no_of_days' => 'nullable|integer|min:0',
            'display_serial' => 'nullable|integer|min:0',
            'apply_limit' => 'nullable|integer|min:0',
            'allow_fractional_leave' => 'required|in:active,inactive',
            'off_day_include' => 'required|in:0,1',
            'active_ind' => 'required|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Leave plan name is required.',
            'name.string' => 'Leave plan name must be a string.',
            'name.max' => 'Leave plan name may not exceed 255 characters.',
            'short_name.string' => 'Short name must be a string.',
            'short_name.max' => 'Short name may not exceed 100 characters.',
            'applicable_gender.required' => 'Please select applicable gender.',
            'applicable_gender.in' => 'The selected gender is invalid.',
            'leave_type.required' => 'Please select leave type.',
            'allow_fractional_leave.required' => 'Please select fractional leave option.',
            'off_day_include.required' => 'Please select off day inclusion option.',
            'active_ind.required' => 'Please select plan status.',
        ];
    }
}
