<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveEncashmentPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'encashment_basis' => 'required|in:basic,gross',
            'min_balance_to_maintain' => 'required|integer|min:0',
            'max_encashable_days_per_year' => 'nullable|integer|min:1',
            'encashment_rate' => 'required|numeric|min:0|max:5',
            'status' => 'required|in:active,inactive',
        ];
    }
}
