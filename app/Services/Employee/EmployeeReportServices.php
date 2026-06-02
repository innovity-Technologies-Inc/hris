<?php

namespace App\Services\Employee;

use App\Models\Company\Company;
use App\Models\Company\Department;
use App\Models\Company\Division;
use App\Models\Company\Section;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeReportServices
{
    /**
     * Get Age Distribution data for charts.
     */
    public function getAgeDistribution(): array
    {
        $employees = Employee::whereNotNull('date_of_birth')->get();
        
        $groups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56+' => 0,
        ];

        foreach ($employees as $employee) {
            $age = Carbon::parse($employee->date_of_birth)->age;
            if ($age >= 18 && $age <= 25) $groups['18-25']++;
            elseif ($age >= 26 && $age <= 35) $groups['26-35']++;
            elseif ($age >= 36 && $age <= 45) $groups['36-45']++;
            elseif ($age >= 46 && $age <= 55) $groups['46-55']++;
            elseif ($age >= 56) $groups['56+']++;
        }

        return [
            'labels' => array_keys($groups),
            'data' => array_values($groups),
        ];
    }

    /**
     * Get detailed age analysis stats.
     */
    public function getAgeAnalysis(): array
    {
        $ages = Employee::whereNotNull('date_of_birth')
            ->get()
            ->map(fn($emp) => Carbon::parse($emp->date_of_birth)->age);

        if ($ages->isEmpty()) {
            return ['avg' => 0, 'min' => 0, 'max' => 0];
        }

        return [
            'avg' => round($ages->avg(), 1),
            'min' => $ages->min(),
            'max' => $ages->max(),
        ];
    }

    /**
     * Get Company-wise employee distribution.
     */
    public function getCompanyDistribution(): array
    {
        $data = EmployeeOfficeInfo::with('getCurrentCompany')
            ->select('current_company_id', DB::raw('count(*) as count'))
            ->groupBy('current_company_id')
            ->get();

        return [
            'labels' => $data->map(fn($item) => $item->getCurrentCompany->name ?? 'Unknown')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    /**
     * Get Hierarchy-wise distribution (Branches/Divisions/Departments/Sections).
     */
    public function getHierarchyDistribution(string $type = 'division', array $filters = []): array
    {
        $mapping = [
            'branch' => [
                'relation' => 'getCurrentBusinessUnit',
                'column' => 'current_business_unit_id',
                'labelCol' => 'name'
            ],
            'division' => [
                'relation' => 'getCurrentDivision',
                'column' => 'current_division_id',
                'labelCol' => 'name'
            ],
            'department' => [
                'relation' => 'getCurrentDepartment',
                'column' => 'current_department_id',
                'labelCol' => 'department_name'
            ],
            'section' => [
                'relation' => 'getCurrentSection',
                'column' => 'current_section_id',
                'labelCol' => 'name'
            ],
        ];

        if (!array_key_exists($type, $mapping)) {
            return ['labels' => [], 'data' => []];
        }

        $relation = $mapping[$type]['relation'];
        $column = $mapping[$type]['column'];
        $labelCol = $mapping[$type]['labelCol'];

        $query = EmployeeOfficeInfo::with($relation)
            ->select($column, DB::raw('count(*) as count'))
            ->whereNotNull($column);

        if (!empty($filters['company_id'])) {
            $query->where('current_company_id', $filters['company_id']);
        }
        if (!empty($filters['business_unit_id'])) {
            $query->where('current_business_unit_id', $filters['business_unit_id']);
        }
        if (!empty($filters['division_id'])) {
            $query->where('current_division_id', $filters['division_id']);
        }
        if (!empty($filters['department_id'])) {
            $query->where('current_department_id', $filters['department_id']);
        }

        $data = $query->groupBy($column)->get();

        return [
            'labels' => $data->map(fn($item) => $item->$relation->{$labelCol} ?? 'Unknown')->toArray(),
            'data' => $data->pluck('count')->toArray(),
        ];
    }

    /**
     * Get Years of Service (Loyalty) data for charts.
     */
    public function getServiceLoyalty(): array
    {
        $officeInfos = EmployeeOfficeInfo::whereNotNull('date_of_join')->get();
        
        $groups = [
            '< 1 Year' => 0,
            '1-3 Years' => 0,
            '3-5 Years' => 0,
            '5-10 Years' => 0,
            '10+ Years' => 0,
        ];

        $now = Carbon::now();

        foreach ($officeInfos as $info) {
            $years = Carbon::parse($info->date_of_join)->diffInYears($now);
            if ($years < 1) $groups['< 1 Year']++;
            elseif ($years >= 1 && $years < 3) $groups['1-3 Years']++;
            elseif ($years >= 3 && $years < 5) $groups['3-5 Years']++;
            elseif ($years >= 5 && $years < 10) $groups['5-10 Years']++;
            elseif ($years >= 10) $groups['10+ Years']++;
        }

        return [
            'labels' => array_keys($groups),
            'data' => array_values($groups),
        ];
    }

    /**
     * Get list of employees with upcoming birthdays (current month).
     */
    public function getUpcomingBirthdays(): array
    {
        $currentMonth = Carbon::now()->month;
        
        return Employee::whereMonth('date_of_birth', $currentMonth)
            ->orderByRaw('DAY(date_of_birth) ASC')
            ->get()
            ->map(function($emp) {
                return [
                    'id' => $emp->id,
                    'full_name' => $emp->full_name,
                    'date' => Carbon::parse($emp->date_of_birth)->format('M d'),
                    'age_upcoming' => Carbon::parse($emp->date_of_birth)->age + 1,
                    'photo' => $emp->photo_path ? asset('storage/' . $emp->photo_path) : null
                ];
            })
            ->toArray();
    }

    /**
     * Get Service Analysis summary data.
     */
    public function getServiceAnalysis(): array
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $newJoineesThisMonth = EmployeeOfficeInfo::whereMonth('date_of_join', Carbon::now()->month)
            ->whereYear('date_of_join', Carbon::now()->year)
            ->count();
        
        // Average Tenure
        $avgTenureDays = EmployeeOfficeInfo::whereNotNull('date_of_join')
            ->select(DB::raw('AVG(DATEDIFF(NOW(), date_of_join)) as avg_days'))
            ->first()
            ->avg_days ?? 0;
        
        $avgTenureYears = round($avgTenureDays / 365, 1);

        return [
            'total' => $totalEmployees,
            'active' => $activeEmployees,
            'new_joinees' => $newJoineesThisMonth,
            'avg_tenure' => $avgTenureYears,
        ];
    }
}
