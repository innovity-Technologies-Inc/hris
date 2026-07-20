<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;

class CalculateEndDateRequest extends FormRequest
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
        return [
            'employee_id' => 'required|exists:employees,id',
            'leave_category_type' => 'nullable|in:standard,compensatory',
            'plan_id' => 'required_if:leave_category_type,standard|nullable',
            'start_date' => 'required|date',
            'leave_count' => 'required|numeric|min:0.5',
        ];
    }
}
