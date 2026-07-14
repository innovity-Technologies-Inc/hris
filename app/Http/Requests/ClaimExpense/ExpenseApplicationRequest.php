<?php

namespace App\Http\Requests\ClaimExpense;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'remarks' => 'nullable|string',
        ];
    }
}
