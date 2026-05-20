<?php

namespace App\Services\Transfer;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Transfer\Transfer;
use App\Models\Transfer\TransferApproval;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Transfer\TransferRequestedNotification;
use App\Notifications\Transfer\TransferApprovedNotification;
use App\Notifications\Transfer\TransferCompletedNotification;

class TransferServices
{
    public function getEmployees()
    {
        return Employee::select('id', 'full_name', 'applicant_id')->get();
    }

    public function storeTransfer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $employee = Employee::with('officeInfo')->findOrFail($data['employee_id']);
            $currentInfo = $employee->officeInfo;

            $transfer = Transfer::create([
                'employee_id' => $data['employee_id'],
                'current_company_id' => $currentInfo->current_company_id ?? null,
                'current_business_unit_id' => $currentInfo->current_business_unit_id ?? null,
                'current_division_id' => $currentInfo->current_division_id ?? null,
                'current_department_id' => $currentInfo->current_department_id ?? null,
                'current_section_id' => $currentInfo->current_section_id ?? null,
                'current_designation_id' => $currentInfo->current_designation_id ?? null,
                'requested_company_id' => $data['requested_company_id'],
                'requested_business_unit_id' => $data['requested_business_unit_id'] ?? null,
                'requested_division_id' => $data['requested_division_id'] ?? null,
                'requested_department_id' => $data['requested_department_id'] ?? null,
                'requested_section_id' => $data['requested_section_id'] ?? null,
                'requested_designation_id' => $data['requested_designation_id'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'created_by' => auth()->id(),
            ]);

            return $transfer;
        });
    }

    public function setApprovers(Transfer $transfer, array $approverIds)
    {
        return DB::transaction(function () use ($transfer, $approverIds) {
            $transfer->transfer_approvals()->delete(); // Clear existing if any

            foreach ($approverIds as $approverId) {
                TransferApproval::create([
                    'transfer_id' => $transfer->id,
                    'approver_id' => $approverId,
                    'status' => 'pending',
                ]);

                // Notify Approver
                $approver = User::find($approverId);
                if ($approver) {
                    $approver->notify(new TransferRequestedNotification($transfer));
                }
            }

            $transfer->update([
                'approval_count_required' => count($approverIds),
                'current_approval_count' => 0,
                'status' => 'pending'
            ]);

            return $transfer;
        });
    }

    public function approveTransfer(Transfer $transfer, User $approver, string $remarks = null)
    {
        return DB::transaction(function () use ($transfer, $approver, $remarks) {
            $approval = TransferApproval::where('transfer_id', $transfer->id)
                ->where('approver_id', $approver->id)
                ->firstOrFail();

            if ($approval->status !== 'pending') {
                throw new \Exception('Already processed.');
            }

            $approval->update([
                'status' => 'approved',
                'remarks' => $remarks,
                'approved_at' => now(),
            ]);

            $transfer->increment('current_approval_count');

            if ($transfer->current_approval_count >= $transfer->approval_count_required) {
                $transfer->update(['status' => 'approved']);
                // Notify HR?
            }

            return $transfer;
        });
    }

    public function completeTransfer(Transfer $transfer)
    {
        return DB::transaction(function () use ($transfer) {
            if ($transfer->status !== 'approved' && $transfer->approval_count_required > 0) {
                throw new \Exception('All approvals required before completion.');
            }

            // Update Employee Office Info
            $officeInfo = EmployeeOfficeInfo::where('employee_id', $transfer->employee_id)->firstOrFail();
            $officeInfo->update([
                'current_company_id' => $transfer->requested_company_id,
                'current_business_unit_id' => $transfer->requested_business_unit_id,
                'current_division_id' => $transfer->requested_division_id,
                'current_department_id' => $transfer->requested_department_id,
                'current_section_id' => $transfer->requested_section_id,
                'current_designation_id' => $transfer->requested_designation_id,
            ]);

            $transfer->update([
                'status' => 'completed',
                'completed_by' => auth()->id(),
                'completed_at' => now(),
            ]);

            // Notify Employee
            $employeeUser = User::where('employee_id', $transfer->employee_id)->first();
            if ($employeeUser) {
                $employeeUser->notify(new TransferCompletedNotification($transfer));
            }

            return $transfer;
        });
    }
}
