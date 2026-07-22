<?php

namespace App\Services\Employee;

use App\Models\Employee\Employee;
use App\Models\Company\Company;
use App\Models\Setting\GeneralSetting;
use App\Enums\UserType;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Support\Facades\Cache;

class EmployeeSearchServices
{
    protected $flexsearch;

    public function __construct(FlexSearch $flexsearch)
    {
        $this->flexsearch = $flexsearch;
    }

    /**
     * Search employees based on filters and keyword.
     */
    public function searchEmployees(array $data): \Illuminate\Database\Eloquent\Collection
    {
        $query = Employee::with([
            'officeInfo.getCurrentCompany',
            'officeInfo.getCurrentBusinessUnit',
            'officeInfo.getCurrentDivision',
            'officeInfo.getCurrentDepartment',
            'officeInfo.getCurrentSection'
        ]);

        // Country filter (JSON permanent_address)
        if (!empty($data['country'])) {
            $query->where('permanent_address->country', $data['country']);
        }

        // Organizational filters via officeInfo
        if (!empty($data['company'])) {
            $query->whereHas('officeInfo', function($q) use ($data) {
                $q->where('current_company_id', $data['company']);
            });
        }

        if (!empty($data['business_unit'])) {
            $query->whereHas('officeInfo', function($q) use ($data) {
                $q->where('current_business_unit_id', $data['business_unit']);
            });
        }

        if (!empty($data['division'])) {
            $query->whereHas('officeInfo', function($q) use ($data) {
                $q->where('current_division_id', $data['division']);
            });
        }

        if (!empty($data['department'])) {
            $query->whereHas('officeInfo', function($q) use ($data) {
                $q->where('current_department_id', $data['department']);
            });
        }

        if (!empty($data['section'])) {
            $query->whereHas('officeInfo', function($q) use ($data) {
                $q->where('current_section_id', $data['section']);
            });
        }

        // Employee type
        if (!empty($data['emp_type'])) {
            $query->whereHas('officeInfo', function($q) use ($data) {
                $q->where('emp_type', $data['emp_type']);
            });
        }

        // Build exact match filters for FlexSearch
        $filters = [];
        if (!empty($data['employee_id'])) {
            $filters['applicant_id'] = $data['employee_id'];
        }
        if (!empty($data['employee_name'])) {
            $filters['full_name'] = $data['employee_name'];
        }
        if (!empty($data['system_id'])) {
            $filters['system_id'] = $data['system_id'];
        }
        if (!empty($data['gender'])) {
            $filters['gender'] = $data['gender'];
        }
        if (!empty($data['marital_status'])) {
            $filters['marital_status'] = $data['marital_status'];
        }
        if (!empty($data['religion'])) {
            $filters['religion'] = $data['religion'];
        }
        if (!empty($data['blood_group'])) {
            $filters['blood_group'] = $data['blood_group'];
        }
        if (!empty($data['nationality'])) {
            $filters['nationality'] = $data['nationality'];
        }

        // Apply age filters if present
        if (isset($data['age_from']) && $data['age_from'] !== '') {
            $maxBirthDate = now()->subYears((int)$data['age_from'])->format('Y-m-d');
            $query->where('date_of_birth', '<=', $maxBirthDate);
        }
        if (isset($data['age_to']) && $data['age_to'] !== '') {
            $minBirthDate = now()->subYears((int)$data['age_to'] + 1)->addDay()->format('Y-m-d');
            $query->where('date_of_birth', '>=', $minBirthDate);
        }

        // FlexSearch columns
        $searchableColumns = [
            'applicant_id',
            'full_name',
            'system_id',
            'personal_mobile',
            'work_email',
            'personal_email'
        ];

        $keyword = $data['keyword'] ?? null;

        return $this->flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->orderBy('id', 'desc')
            ->get();
    }

    /**
     * Get unique filter options for dropdown population.
     */
    public function getFilterOptions(): array
    {
        $employees = Employee::with([
            'officeInfo.getCurrentCompany',
            'officeInfo.getCurrentBusinessUnit',
            'officeInfo.getCurrentDivision',
            'officeInfo.getCurrentDepartment',
            'officeInfo.getCurrentSection'
        ])
        ->select(
            'id',
            'applicant_id',
            'system_id',
            'full_name',
            'gender',
            'marital_status',
            'blood_group',
            'religion',
            'nationality',
            'date_of_birth',
            'permanent_address',
            'personal_mobile',
            'work_email'
        )->get();

        $cachedFilters = Cache::remember('employee_search_filters_data', 3600, function() use ($employees) {
            return [
                'employee_names' => $employees->pluck('full_name')->unique()->sort()->values()->toArray(),
                'employee_ids' => $employees->pluck('applicant_id')->unique()->sort()->values()->toArray(),
                'system_ids' => $employees->pluck('system_id')->unique()->sort()->values()->toArray(),
                'religions' => $employees->pluck('religion')->filter()->unique()->sort()->values()->toArray(),
                'nationalities' => $employees->pluck('nationality')->filter()->unique()->sort()->values()->toArray(),
                'countries' => $employees->map(function($emp) {
                    return $emp->permanent_address['country'] ?? null;
                })->filter()->unique()->sort()->values()->toArray(),
            ];
        });

        $companies = Company::select('id', 'name')->orderBy('name')->get();

        return [
            'employees' => $employees,
            'employee_names' => collect($cachedFilters['employee_names']),
            'employee_ids' => collect($cachedFilters['employee_ids']),
            'system_ids' => collect($cachedFilters['system_ids']),
            'genders' => ['Male', 'Female', 'Other'],
            'marital_statuses' => ['Single', 'Married', 'Divorced', 'Widowed'],
            'employee_types' => ['permanent', 'contractual'],
            'blood_groups' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
            'religions' => collect($cachedFilters['religions']),
            'nationalities' => collect($cachedFilters['nationalities']),
            'countries' => collect($cachedFilters['countries']),
            'companies' => $companies,
        ];
    }
}
