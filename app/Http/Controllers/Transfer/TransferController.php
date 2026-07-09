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

    public function adjustment()
    {
        $transfers = Transfer::withoutGlobalScopes()
            ->where('status', 'approved')
            ->where('is_adjustment', 1)
            ->whereDate('effective_from', '<=', \Carbon\Carbon::today())
            ->get();

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($transfers) {
                $service = app(\App\Services\Transfer\TransferServices::class);
                foreach ($transfers as $transfer) {
                    $service->completeTransfer($transfer);
                }
            });

            return redirect()->route('transfer.index')->with([
                'message' => 'Adjustments processed successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Transfer Adjustment Error: ' . $e->getMessage());
            return redirect()->route('transfer.index')->with([
                'message' => 'Something went wrong.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function delete($id)
    {
        $transfer = Transfer::withoutGlobalScopes()->findOrFail($id);

        if ($transfer->status !== 'pending') {
            return redirect()->back()->with([
                'message' => 'Only pending transfers can be deleted.',
                'alert-type' => 'error'
            ]);
        }

        $transfer->delete();

        return redirect()->route('transfer.index')->with([
            'message' => 'Transfer record deleted successfully.',
            'alert-type' => 'success'
        ]);
    }
}
