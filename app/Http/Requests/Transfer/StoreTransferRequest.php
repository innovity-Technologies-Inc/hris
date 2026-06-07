<?php

namespace App\Http\Requests\Transfer;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('transfers.create');
    }

    public function rules(): array
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'requested_company_id' => 'required|exists:companies,id',
            'requested_business_unit_id' => 'nullable|exists:company_locations,id',
            'requested_division_id' => 'nullable|exists:divisions,id',
            'requested_department_id' => 'nullable|exists:departments,id',
            'requested_section_id' => 'nullable|exists:sections,id',
            'remarks' => 'nullable|string|max:500',
        ];

        if (auth()->check() && auth()->user()->user_type === UserType::Employee) {
            $rules['employee_id'] = 'required|in:' . auth()->user()->employee_id;
            
            $setting = \App\HelperClass::getTransferSetting();
            $level = $setting->employee_transfer_level ?? 'company';
            $this->validateLevelRestriction($rules, $level, auth()->user()->employee_id);
        } else {
            $setting = \App\HelperClass::getTransferSetting();
            $level = $setting->supervisor_transfer_level ?? 'company';
            if ($this->employee_id) {
                $this->validateLevelRestriction($rules, $level, $this->employee_id);
            }
        }

        return $rules;
    }

    private function validateLevelRestriction(&$rules, $level, $employeeId)
    {
        $levels = ['company' => 1, 'business_unit' => 2, 'division' => 3, 'department' => 4, 'section' => 5];
        $weight = $levels[$level] ?? 1;
        
        $officeInfo = \App\Models\Employee\EmployeeOfficeInfo::where('employee_id', $employeeId)->first();
        if (!$officeInfo) return;

        if ($weight > 1) $rules['requested_company_id'] = 'required|in:' . $officeInfo->current_company_id;
        if ($weight > 2) $rules['requested_business_unit_id'] = 'nullable|in:' . $officeInfo->current_business_unit_id;
        if ($weight > 3) $rules['requested_division_id'] = 'nullable|in:' . $officeInfo->current_division_id;
        if ($weight > 4) $rules['requested_department_id'] = 'nullable|in:' . $officeInfo->current_department_id;
        if ($weight > 5) $rules['requested_section_id'] = 'nullable|in:' . $officeInfo->current_section_id;
    }
}
