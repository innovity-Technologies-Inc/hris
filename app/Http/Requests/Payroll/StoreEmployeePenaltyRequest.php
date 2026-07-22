<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeePenaltyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'penalty_plan_id' => 'required|exists:penalty_plans,id',
            'occurrence_date' => 'required|date',
            'cause' => 'nullable|string',
            'penalty_amount' => 'required|numeric|min:0',
        ];
    }
}
