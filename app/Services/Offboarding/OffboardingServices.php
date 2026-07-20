<?php

namespace App\Services\Offboarding;

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Division;
use App\Models\Company\Section;
use App\Models\Employee\Employee;
use App\Models\Offboarding\Offboarding;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OffboardingServices
{
    /**
     * Get paginated offboarding records by type ('resignation' or 'termination').
     */
    public function getOffboardingsPaginated(FlexSearch $flexsearch, Request $request, string $type)
    {
        $query = Offboarding::withoutGlobalScopes()
            ->with(['employee.officeInfo', 'creator'])
            ->where('offboarding_type', $type)
            ->latest();

        $searchableColumns = ['employee.full_name', 'reason', 'status', 'remarks'];

        return $flexsearch->apply($query, [], $request->get('keyword'), $searchableColumns)
            ->paginate(15);
    }

    /**
     * Get master data for form dropdowns.
     */
    public function getFormMasterData()
    {
        $companies = Company::orderBy('name')->get();
        $branches = CompanyLocation::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $departments = Department::orderBy('department_name')->get();
        $sections = Section::orderBy('name')->get();

        return compact('companies', 'branches', 'divisions', 'departments', 'sections');
    }

    /**
     * Get employees filtered by hierarchy.
     */
    public function getEmployeesByHierarchy(array $filters)
    {
        $query = Employee::select('id', 'full_name', 'applicant_id');

        if (!empty($filters['company_id']) || !empty($filters['branch_id']) || !empty($filters['division_id']) || !empty($filters['department_id']) || !empty($filters['section_id'])) {
            $query->whereHas('officeInfo', function ($q) use ($filters) {
                if (!empty($filters['company_id'])) {
                    $q->where('current_company_id', $filters['company_id']);
                }
                if (!empty($filters['branch_id'])) {
                    $q->where('current_business_unit_id', $filters['branch_id']);
                }
                if (!empty($filters['division_id'])) {
                    $q->where('current_division_id', $filters['division_id']);
                }
                if (!empty($filters['department_id'])) {
                    $q->where('current_department_id', $filters['department_id']);
                }
                if (!empty($filters['section_id'])) {
                    $q->where('current_section_id', $filters['section_id']);
                }
            });
        }

        return $query->orderBy('full_name')->get();
    }

    /**
     * Store an offboarding record, update employee status, and trigger approval workflow.
     */
    public function storeOffboarding(array $data, $user): Offboarding
    {
        return DB::transaction(function () use ($data, $user) {
            $data['created_by'] = $user->id;
            $data['status'] = 'pending';

            $offboarding = Offboarding::create($data);

            // Immediately update target employee status to 'resigned' or 'terminated'
            $employee = Employee::withoutGlobalScopes()->find($data['employee_id']);
            if ($employee) {
                $newStatus = $data['offboarding_type'] === 'termination' ? 'terminated' : 'resigned';
                $employee->update(['status' => $newStatus]);
            }

            // Trigger approval workflow engine for offboarding type
            if (method_exists($offboarding, 'startWorkflow')) {
                $moduleName = 'offboarding-' . $data['offboarding_type'];
                $offboarding->startWorkflow($moduleName);
            }

            return $offboarding;
        });
    }

    /**
     * Update an offboarding record.
     */
    public function updateOffboarding(int $id, array $data, $user): Offboarding
    {
        return DB::transaction(function () use ($id, $data, $user) {
            $offboarding = Offboarding::withoutGlobalScopes()->findOrFail($id);
            $data['updated_by'] = $user->id;

            $offboarding->update($data);

            // If status changed to approved, ensure employee status is set
            if (isset($data['status']) && in_array($data['status'], ['approved', 'pending'])) {
                $employee = Employee::withoutGlobalScopes()->find($offboarding->employee_id);
                if ($employee) {
                    $newStatus = $offboarding->offboarding_type === 'termination' ? 'terminated' : 'resigned';
                    $employee->update(['status' => $newStatus]);
                }
            }

            return $offboarding;
        });
    }

    /**
     * Delete an offboarding record.
     */
    public function deleteOffboarding(int $id, $user): bool
    {
        return DB::transaction(function () use ($id) {
            $offboarding = Offboarding::withoutGlobalScopes()->findOrFail($id);
            return $offboarding->delete();
        });
    }

    /**
     * Get offboarding details by ID.
     */
    public function getOffboardingById(int $id): Offboarding
    {
        return Offboarding::withoutGlobalScopes()->with([
            'employee.officeInfo.company',
            'employee.officeInfo.designation',
            'creator',
            'updater',
            'approvalRequests.stepRequests.workflowStep'
        ])->findOrFail($id);
    }

    /**
     * Get offboarding details for logged in offboarded employee.
     */
    public function getEmployeeOffboardingDetails($user)
    {
        if (!$user || !$user->employee_id) {
            return null;
        }

        return Offboarding::withoutGlobalScopes()
            ->with(['employee.officeInfo.company', 'employee.officeInfo.designation', 'creator'])
            ->where('employee_id', $user->employee_id)
            ->latest()
            ->first();
    }
}
