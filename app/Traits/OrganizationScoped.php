<?php

namespace App\Traits;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait OrganizationScoped
{
    protected static $tableColumnsCache = [];
    protected static $authEmployeeCache = [];

    protected static function bootOrganizationScoped()
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                $userId = Auth::id();
                
                // Super Admin or Group type sees everything
                // Modified: Only Group type sees everything by default. 
                // If a user (even Super Admin) has a specific organizational type (Company, Division, etc.), 
                // they should be filtered by that scope.
                if ($user->user_type === UserType::Group) {
                    return;
                }

                // Use a static cache to avoid recursion and multiple queries
                // Bypass cache in unit tests to prevent database state contamination between tests.
                if (app()->runningUnitTests() || !array_key_exists($userId, static::$authEmployeeCache)) {
                    // We must use withoutGlobalScopes() here to avoid recursion
                    static::$authEmployeeCache[$userId] = $user->employee()
                        ->withoutGlobalScopes()
                        ->with(['officeInfo' => function($q) {
                            $q->withoutGlobalScopes();
                        }])
                        ->first();
                }

                $employee = static::$authEmployeeCache[$userId];
                $table = $builder->getModel()->getTable();
                
                // Static cache for table columns to avoid multiple schema calls in a single request
                if (!isset(static::$tableColumnsCache[$table])) {
                    static::$tableColumnsCache[$table] = Schema::getColumnListing($table);
                }

                $hasColumn = function($col) use ($table) {
                    return in_array($col, static::$tableColumnsCache[$table]);
                };

                if (!$employee || !$employee->officeInfo) {
                    // For users with no office info link, default to own data if they are an Employee
                    if ($user->user_type === UserType::Employee) {
                        if ($table === 'employees') {
                            $builder->where('id', $user->employee_id);
                        } elseif ($hasColumn('employee_id')) {
                            $builder->where('employee_id', $user->employee_id);
                        }
                    }
                    return;
                }

                $office = $employee->officeInfo;

                $levelMapping = [
                    UserType::Company->value => [
                        'self_table' => 'companies',
                        'value' => $office->current_company_id,
                        'columns' => ['company_id', 'current_company_id'],
                        'office_col' => 'current_company_id'
                    ],
                    UserType::Division->value => [
                        'self_table' => 'divisions',
                        'value' => $office->current_division_id,
                        'columns' => ['division_id', 'current_division_id'],
                        'office_col' => 'current_division_id',
                        'parent_scopes' => [
                            'companies' => ['col' => 'id', 'val' => $office->current_company_id]
                        ]
                    ],
                    UserType::Department->value => [
                        'self_table' => 'departments',
                        'value' => $office->current_department_id,
                        'columns' => ['department_id', 'current_department_id'],
                        'office_col' => 'current_department_id',
                        'parent_scopes' => [
                            'companies' => ['col' => 'id', 'val' => $office->current_company_id],
                            'divisions' => ['col' => 'id', 'val' => $office->current_division_id]
                        ]
                    ],
                    UserType::Section->value => [
                        'self_table' => 'sections',
                        'value' => $office->current_section_id,
                        'columns' => ['section_id', 'current_section_id'],
                        'office_col' => 'current_section_id',
                        'parent_scopes' => [
                            'companies' => ['col' => 'id', 'val' => $office->current_company_id],
                            'divisions' => ['col' => 'id', 'val' => $office->current_division_id],
                            'departments' => ['col' => 'id', 'val' => $office->current_department_id]
                        ]
                    ],
                    UserType::BusinessUnit->value => [
                        'self_table' => 'company_locations',
                        'value' => $office->current_business_unit_id,
                        'columns' => ['business_unit_id', 'current_business_unit_id', 'location_id', 'branch_id'],
                        'office_col' => 'current_business_unit_id'
                    ],
                ];

                if (isset($levelMapping[$user->user_type->value])) {
                    $map = $levelMapping[$user->user_type->value];
                    $model = $builder->getModel();
                    
                    if ($table === 'employees') {
                        $builder->whereHas('officeInfo', function($q) use ($map) {
                            $q->where($map['office_col'], $map['value']);
                        });
                    } elseif ($table === $map['self_table']) {
                        $builder->where('id', $map['value']);
                    } elseif (isset($map['parent_scopes'][$table])) {
                        $parent = $map['parent_scopes'][$table];
                        $builder->where($parent['col'], $parent['val']);
                    } else {
                        $columnFound = false;
                        foreach ($map['columns'] as $col) {
                            if ($hasColumn($col)) {
                                $builder->where($col, $map['value']);
                                $columnFound = true;
                                break;
                            }
                        }

                        if (!$columnFound && $hasColumn('employee_id')) {
                            // Determine the correct relationship name (employee or getEmployee)
                            $rel = method_exists($model, 'employee') ? 'employee' : 
                                  (method_exists($model, 'getEmployee') ? 'getEmployee' : null);
                            
                            if ($rel) {
                                $builder->whereHas($rel . '.officeInfo', function($q) use ($map) {
                                    $q->where($map['office_col'], $map['value']);
                                });
                            }
                        }
                    }
                } elseif ($user->user_type === UserType::Employee) {
                    if ($table === 'employees') {
                        $builder->where('id', $user->employee_id);
                    } elseif ($hasColumn('employee_id')) {
                        $builder->where('employee_id', $user->employee_id);
                    }
                }
            }
        });
    }
}

