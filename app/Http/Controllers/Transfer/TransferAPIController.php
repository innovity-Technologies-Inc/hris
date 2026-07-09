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
        try {
            $transfers = $this->transferServices->getTransferList($request->all());
            
            $canDelete = auth()->user()->can('transfers.delete');
            $transfers->getCollection()->transform(function($transfer) use ($canDelete) {
                $transfer->can_delete = $canDelete;
                return $transfer;
            });

            return response()->json([
                'success' => true,
                'data' => $transfers
            ]);
        } catch (\Exception $e) {
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
}
