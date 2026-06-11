<?php

namespace App\Http\Controllers\Transfer;

use App\Http\Controllers\Controller;
use App\Models\Transfer\Transfer;
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

        $weights = [
            'company' => 1,
            'business_unit' => 2,
            'division' => 3,
            'department' => 4,
            'section' => 5,
        ];

        $level = $isEmployee ? ($setting->employee_transfer_level ?? 'company') : ($setting->supervisor_transfer_level ?? 'company');
        $levelWeight = $weights[$level] ?? 1;

        return view('transfer.application', compact('title', 'section', 'setting', 'isEmployee', 'loggedInEmployeeId', 'levelWeight'));
    }

    public function show($id)
    {
        $title = 'Career Movement Details';
        $section = 'Career Movement';
        $transfer = Transfer::withoutGlobalScopes()->with([
            'employee.officeInfo',
            'currentCompany', 'currentBusinessUnit', 'currentDivision', 'currentDepartment', 'currentSection',
            'requestedCompany', 'requestedBusinessUnit', 'requestedDivision', 'requestedDepartment', 'requestedSection',
            'approvals.approver', 'creator', 'completer'
        ])->findOrFail($id);

        return view('transfer.view', compact('title', 'section', 'transfer'));
    }
}
