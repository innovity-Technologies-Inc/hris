<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            
            'zero_tax_male' => 'required|numeric|min:0',
            'zero_tax_female' => 'required|numeric|min:0',
            'min_tax_amount' => 'required|numeric|min:0',
            
            'exemption_type' => 'required|in:fixed,exempt_allowance',
            'salary_ratio' => 'required_if:exemption_type,fixed|nullable|string',
            'fixed_amount' => 'required_if:exemption_type,fixed|nullable|numeric|min:0',
            
            'exempt_allowances' => 'required_if:exemption_type,exempt_allowance|nullable|array',
            'exempt_allowances.*' => 'required|string',
            
            'slabs' => 'nullable|array',
            'slabs.*.taxable_amount' => 'required|numeric|min:0',
            'slabs.*.tax_percentage' => 'required|numeric|min:0|max:100',
            'slabs.*.tax_amount' => 'required|numeric|min:0',
        ];
    }
}
