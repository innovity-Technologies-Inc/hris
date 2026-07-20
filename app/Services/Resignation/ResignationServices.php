<?php

namespace App\Services\Resignation;

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Division;
use App\Models\Company\Section;
use App\Models\Employee\Employee;
use App\Models\Resignation\Resignation;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResignationServices
{
    /**
     * Get paginated resignations using FlexSearch.
     */
    public function getResignationsPaginated(FlexSearch $flexsearch, Request $request)
    {
        $query = Resignation::with(['employee.officeInfo', 'creator'])
            ->latest();

        $searchableColumns = ['employee.full_name', 'reason', 'status', 'remarks'];

        return $flexsearch->apply($query, [], $request->get('keyword'), $searchableColumns)
            ->paginate(15);
    }

    /**
     * Get data required for create/edit form.
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
     * Get filtered employees based on 5-tier organizational hierarchy.
     */
    public function getEmployeesByHierarchy(array $filters)
    {
        $query = Employee::select('id', 'full_name', 'applicant_id')
            ->where('status', 'active');

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
     * Store resignation and trigger approval workflow engine.
     */
    public function storeResignation(array $data, $user): Resignation
    {
        return DB::transaction(function () use ($data, $user) {
            $data['created_by'] = $user->id;
            $data['status'] = 'pending';

            $resignation = Resignation::create($data);

            // Trigger approval workflow engine for resign module
            if (method_exists($resignation, 'startWorkflow')) {
                $resignation->startWorkflow('resign');
            }

            return $resignation;
        });
    }

    /**
     * Update an existing resignation.
     */
    public function updateResignation(int $id, array $data, $user): Resignation
    {
        return DB::transaction(function () use ($id, $data, $user) {
            $resignation = Resignation::withoutGlobalScopes()->findOrFail($id);
            $data['updated_by'] = $user->id;

            $resignation->update($data);

            return $resignation;
        });
    }

    /**
     * Delete a resignation record.
     */
    public function deleteResignation(int $id, $user): bool
    {
        return DB::transaction(function () use ($id) {
            $resignation = Resignation::withoutGlobalScopes()->findOrFail($id);
            return $resignation->delete();
        });
    }

    /**
     * Get resignation details by ID.
     */
    public function getResignationById(int $id): Resignation
    {
        return Resignation::withoutGlobalScopes()->with([
            'employee.officeInfo.company',
            'employee.officeInfo.designation',
            'creator',
            'updater',
            'approvalRequests.stepRequests.workflowStep'
        ])->findOrFail($id);
    }
}
