<?php

namespace App\Http\Controllers\Transfer;

use App\Http\Controllers\Controller;
use App\Models\Transfer\Transfer;
use App\Services\Transfer\TransferServices;
use App\Exports\Transfer\TransferExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $title = 'Career Movement Logs';
        $section = 'Career Movement';
        return view('transfer.logs', compact('title', 'section'));
    }

    public function create()
    {
        $title = 'Career Movement Application';
        $section = 'Career Movement';
        $setting = \App\HelperClass::getTransferSetting();
        $user = auth()->user();
        $isEmployee = $user->user_type === \App\Enums\UserType::Employee;
        $loggedInEmployeeId = $user->employee_id;

        $level = $isEmployee ? ($setting->employee_transfer_level ?? 'company') : ($setting->supervisor_transfer_level ?? 'company');
        $levelWeight = \App\Enums\UserType::getWeight($level);

        $movementTypes = \App\Models\Company\MovementType::where('status', 'active')->get();

        return view('transfer.application', compact('title', 'section', 'setting', 'isEmployee', 'loggedInEmployeeId', 'levelWeight', 'movementTypes'));
    }

    public function show($id)
    {
        $title = 'Career Movement Details';
        $section = 'Career Movement';
        $transfer = Transfer::withoutGlobalScopes()->with([
            'employee.officeInfo',
            'currentCompany', 'currentBusinessUnit', 'currentDivision', 'currentDepartment', 'currentSection',
            'requestedCompany', 'requestedBusinessUnit', 'requestedDivision', 'requestedDepartment', 'requestedSection',
            'approvalRequests.stepRequests.workflowStep', 'creator', 'completer'
        ])->findOrFail($id);

        return view('transfer.view', compact('title', 'section', 'transfer'));
    }

    public function exportExcel(Request $request, TransferServices $transferServices)
    {
        $records = $transferServices->getTransferList($request->all(), false);
        return Excel::download(new TransferExport($records), 'career_movements_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function printIndex(Request $request, TransferServices $transferServices)
    {
        $records = $transferServices->getTransferList($request->all(), false);
        return view('transfer.print_index', compact('records'));
    }
}
