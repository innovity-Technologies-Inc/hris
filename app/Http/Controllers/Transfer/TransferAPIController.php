<?php

namespace App\Http\Controllers\Transfer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transfer\StoreTransferRequest;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use App\Models\Company\Designation;
use App\Models\Company\Division;
use App\Models\Company\Section;
use App\Models\Employee\Employee;
use App\Models\Transfer\Transfer;
use App\Models\User;
use App\Services\Transfer\TransferServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransferAPIController extends Controller
{
    protected $transferServices;

    public function __construct(TransferServices $transferServices)
    {
        $this->transferServices = $transferServices;
    }

    public function getEmployees()
    {
        try {
            $employees = $this->transferServices->getEmployees();
            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCompanies()
    {
        return response()->json(['success' => true, 'data' => Company::select('id', 'name')->get()]);
    }

    public function getUnits($companyId)
    {
        $units = CompanyLocation::where('company_id', $companyId)->select('id', 'name')->get();
        return response()->json(['success' => true, 'data' => $units]);
    }

    public function getDivisions($companyId, $locationId)
    {
        $query = Division::where('company_id', $companyId);
        if ($locationId !== 'null') {
            $query->where('location_id', $locationId);
        }
        return response()->json(['success' => true, 'data' => $query->select('id', 'name')->get()]);
    }

    public function getDepartments($companyId, $locationId, $divisionId)
    {
        $query = Department::where('company_id', $companyId);
        if ($locationId !== 'null') $query->where('location_id', $locationId);
        if ($divisionId !== 'null') $query->where('division_id', $divisionId);
        return response()->json(['success' => true, 'data' => $query->select('id', 'department_name', 'department_name as name')->get()]);
    }

    public function getSections($companyId, $locationId, $divisionId, $departmentId)
    {
        $query = Section::where('company_id', $companyId);
        if ($locationId !== 'null') $query->where('location_id', $locationId);
        if ($divisionId !== 'null') $query->where('division_id', $divisionId);
        if ($departmentId !== 'null') $query->where('department_id', $departmentId);
        return response()->json(['success' => true, 'data' => $query->select('id', 'name')->get()]);
    }

    public function getDesignations()
    {
        return response()->json(['success' => true, 'data' => Designation::select('id', 'company_designation as name')->get()]);
    }

    public function store(StoreTransferRequest $request)
    {
        try {
            $transfer = $this->transferServices->storeTransfer($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Transfer application submitted successfully.',
                'data' => $transfer
            ]);
        } catch (\Exception $e) {
            Log::error('Transfer Store Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        
        // We use withoutGlobalScopes to manually handle the union of "In Scope" OR "Assigned Approver"
        $query = Transfer::withoutGlobalScopes()
            ->with(['employee', 'requestedCompany', 'requestedBusinessUnit']);

        // 1. Apply Scoping / Permissions
        if ($user->user_type !== 'Group') {
            $query->where(function($q) use ($user) {
                // Own scope (mirrors OrganizationScoped logic manually here to allow OR)
                $q->where(function($sq) use ($user) {
                    $employee = $user->employee()->with('officeInfo')->first();
                    if ($employee && $employee->officeInfo) {
                        $office = $employee->officeInfo;
                        if ($user->user_type === 'Company') $sq->where('current_company_id', $office->current_company_id);
                        elseif ($user->user_type === 'Business Unit') $sq->where('current_business_unit_id', $office->current_business_unit_id);
                        elseif ($user->user_type === 'Division') $sq->where('current_division_id', $office->current_division_id);
                        elseif ($user->user_type === 'Department') $sq->where('current_department_id', $office->current_department_id);
                        elseif ($user->user_type === 'Section') $sq->where('current_section_id', $office->current_section_id);
                    }
                    if ($user->user_type === 'Employee') {
                        $sq->orWhere('employee_id', $user->employee_id);
                    }
                });

                // OR Assigned Approver (Cross-scope visibility)
                $q->orWhereHas('approvals', function($aq) use ($user) {
                    $aq->where('approver_id', $user->id);
                });
                
                // OR Creator
                $q->orWhere('created_by', $user->id);
            });
        }

        // 2. Advanced Filters (Employee Name, Applicant ID, System ID)
        if ($request->employee_search) {
            $search = $request->employee_search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('applicant_id', 'like', "%{$search}%")
                  ->orWhere('system_id', 'like', "%{$search}%");
            });
        }

        // Requested Organization Data
        if ($request->requested_company_id) {
            $query->where('requested_company_id', $request->requested_company_id);
        }
        if ($request->requested_business_unit_id) {
            $query->where('requested_business_unit_id', $request->requested_business_unit_id);
        }
        if ($request->requested_division_id) {
            $query->where('requested_division_id', $request->requested_division_id);
        }
        if ($request->requested_department_id) {
            $query->where('requested_department_id', $request->requested_department_id);
        }
        if ($request->requested_section_id) {
            $query->where('requested_section_id', $request->requested_section_id);
        }

        $transfers = $query->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => $transfers
        ]);
    }

    public function setApprovers(Request $request, $id)
    {
        $request->validate([
            'approver_ids' => 'required|array',
            'approver_ids.*' => 'exists:users,id'
        ]);

        try {
            $transfer = Transfer::withoutGlobalScopes()->findOrFail($id);
            $this->transferServices->setApprovers($transfer, $request->approver_ids);
            return response()->json(['success' => true, 'message' => 'Approvers assigned and notified.']);
        } catch (\Exception $e) {
            Log::error('Transfer setApprovers failed: ' . $e->getMessage(), ['id' => $id, 'ids' => $request->approver_ids]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function approve(Request $request, $id)
    {
        try {
            $transfer = Transfer::withoutGlobalScopes()->findOrFail($id);
            Log::info('Approval attempt:', ['transfer_id' => $id, 'user_id' => auth()->id()]);
            $this->transferServices->approveTransfer($transfer, auth()->user(), $request->remarks);
            return response()->json(['success' => true, 'message' => 'Transfer approved.']);
        } catch (\Exception $e) {
            Log::error('Transfer approve failed: ' . $e->getMessage(), ['id' => $id, 'user_id' => auth()->id()]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function complete($id)
    {
        try {
            $transfer = Transfer::withoutGlobalScopes()->findOrFail($id);
            $this->transferServices->completeTransfer($transfer);
            return response()->json(['success' => true, 'message' => 'Transfer completed and office info updated.']);
        } catch (\Exception $e) {
            Log::error('Transfer complete failed: ' . $e->getMessage(), ['id' => $id]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function searchAuthorities(Request $request)
    {
        // Only return users who actually have the permission to approve
        $query = User::permission('transfers.approve')
            ->with(['employee.officeInfo' => function($q) {
                $q->withoutGlobalScopes();
            }, 'employee.officeInfo.getCurrentCompany', 'employee.officeInfo.getCurrentBusinessUnit']);

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        if ($request->user_type) {
            $query->where('user_type', $request->user_type);
        }

        // Search in employee office info
        if ($request->company_id || $request->unit_id || $request->division_id || $request->department_id || $request->section_id) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->withoutGlobalScopes(); // Important: bypass scoping to find authorities from other scopes
                if ($request->company_id) $q->where('current_company_id', $request->company_id);
                if ($request->unit_id) $q->where('current_business_unit_id', $request->unit_id);
                if ($request->division_id) $q->where('current_division_id', $request->division_id);
                if ($request->department_id) $q->where('current_department_id', $request->department_id);
                if ($request->section_id) $q->where('current_section_id', $request->section_id);
            });
        }

        $authorities = $query->limit(30)->get();

        return response()->json([
            'success' => true,
            'data' => $authorities
        ]);
    }
}
