<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait OrganizationScoped
{
    protected static function bootOrganizationScoped()
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                $employee = $user->employee;
                
                // Super Admin or Group type sees everything
                if ($user->hasRole('Super Admin') || $user->user_type === 'Group') {
                    return;
                }

                if (!$employee || !$employee->officeInfo) {
                    // If no employee link, maybe default to no access or only own data
                    if ($user->user_type === 'Employee') {
                        $builder->where('employee_id', $user->employee_id);
                    }
                    return;
                }

                $office = $employee->officeInfo;

                switch ($user->user_type) {
                    case 'Company':
                        $builder->where('company_id', $office->current_company_id);
                        break;
                    case 'Division':
                        $builder->where('division_id', $office->current_division_id);
                        break;
                    case 'Department':
                        $builder->where('department_id', $office->current_department_id);
                        break;
                    case 'Section':
                        $builder->where('section_id', $office->current_section_id);
                        break;
                    case 'Business Unit':
                        $builder->where('business_unit_id', $office->current_business_unit_id);
                        break;
                    case 'Employee':
                        // For Employee type, usually they only see their own records
                        // Check if the table has employee_id or if it's the employees table itself
                        if ($builder->getModel()->getTable() === 'employees') {
                            $builder->where('id', $user->employee_id);
                        } else {
                            $builder->where('employee_id', $user->employee_id);
                        }
                        break;
                }
            }
        });
    }
}
