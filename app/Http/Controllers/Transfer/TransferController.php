<?php

namespace App\Http\Controllers\Transfer;

use App\Http\Controllers\Controller;
use App\Models\Transfer\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        $title = 'Transfer Logs';
        $section = 'Transfer';
        return view('transfer.logs', compact('title', 'section'));
    }

    public function create()
    {
        $title = 'Transfer Application';
        $section = 'Transfer';
        $setting = \App\HelperClass::getTransferSetting();
        return view('transfer.application', compact('title', 'section', 'setting'));
    }

    public function show($id)
    {
        $title = 'Transfer Details';
        $section = 'Transfer';
        $transfer = Transfer::with([
            'employee.officeInfo',
            'currentCompany', 'currentBusinessUnit', 'currentDivision', 'currentDepartment', 'currentSection', 'currentDesignation',
            'requestedCompany', 'requestedBusinessUnit', 'requestedDivision', 'requestedDepartment', 'requestedSection', 'requestedDesignation',
            'approvals.approver'
        ])->findOrFail($id);

        return view('transfer.view', compact('title', 'section', 'transfer'));
    }
}
