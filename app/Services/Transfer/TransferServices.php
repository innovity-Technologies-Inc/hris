<?php

namespace App\Services\Transfer;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Transfer\Transfer;
use App\Models\Transfer\TransferApproval;
use App\Models\User;
use App\Services\Setting\NotificationServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Transfer\TransferRequestedNotification;
use App\Notifications\Transfer\TransferApprovedNotification;
use App\Notifications\Transfer\TransferCompletedNotification;

class TransferServices
{
    protected $notificationService;

    public function __construct(NotificationServices $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getEmployees()
    {
        return Employee::select('id', 'full_name', 'applicant_id')->get();
    }

    public function storeTransfer(array $data)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error storing transfer application: ' . $e->getMessage(), [
                'data' => $data,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function setApprovers(Transfer $transfer, array $approverIds)
    {
        try {
            return DB::transaction(function () use ($transfer, $approverIds) {
                $transfer->approvals()->delete(); // Clear existing if any

                foreach ($approverIds as $approverId) {
                    TransferApproval::create([
                        'transfer_id' => $transfer->id,
                        'approver_id' => $approverId,
                        'status' => 'pending',
                    ]);

                    // Notify Approver (System + Laravel)
                    $approver = User::find($approverId);
                    if ($approver) {
                        try {
                            $approver->notify(new TransferRequestedNotification($transfer));
                            
                            $this->notificationService->createNotification(
                                $approver->user_type,
                                $approver->id,
                                'New Transfer Approval Required',
                                'You have a pending transfer approval for ' . $transfer->employee->full_name,
                                ['transfer_id' => $transfer->id]
                            );
                        } catch (\Exception $ne) {
                            Log::warning('Failed to notify approver for transfer request: ' . $ne->getMessage(), [
                                'transfer_id' => $transfer->id,
                                'approver_id' => $approverId
                            ]);
                        }
                    }
                }

                $transfer->update([
                    'approval_count_required' => count($approverIds),
                    'current_approval_count' => 0,
                    'status' => 'pending'
                ]);

                return $transfer;
            });
        } catch (\Exception $e) {
            Log::error('Error setting approvers for transfer: ' . $e->getMessage(), [
                'transfer_id' => $transfer->id,
                'approver_ids' => $approverIds,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function approveTransfer(Transfer $transfer, User $approver, string $remarks = null)
    {
        try {
            return DB::transaction(function () use ($transfer, $approver, $remarks) {
                $approval = TransferApproval::where('transfer_id', $transfer->id)
                    ->where('approver_id', $approver->id)
                    ->lockForUpdate() // Prevent race conditions
                    ->firstOrFail();

                if ($approval->status !== 'pending') {
                    throw new \Exception('This approval request has already been processed.');
                }

                $approval->update([
                    'status' => 'approved',
                    'remarks' => $remarks,
                    'approved_at' => now(),
                ]);

                // Recalculate count to be safe from race conditions
                $approvedCount = TransferApproval::where('transfer_id', $transfer->id)
                    ->where('status', 'approved')
                    ->count();

                $transfer->update([
                    'current_approval_count' => $approvedCount
                ]);

                if ($approvedCount >= $transfer->approval_count_required) {
                    $transfer->update(['status' => 'approved']);
                    Log::info('Transfer request fully approved.', ['transfer_id' => $transfer->id]);
                }

                // Notify Creator (System + Laravel)
                try {
                    $creator = User::find($transfer->created_by);
                    if ($creator) {
                        $creator->notify(new TransferApprovedNotification($transfer));

                        $this->notificationService->createNotification(
                            $creator->user_type,
                            $creator->id,
                            'Transfer Request Update',
                            'An authority has approved your transfer request for ' . $transfer->employee->full_name,
                            ['transfer_id' => $transfer->id]
                        );
                    }
                } catch (\Exception $ne) {
                    Log::warning('Failed to notify creator of approved transfer: ' . $ne->getMessage(), [
                        'transfer_id' => $transfer->id,
                        'creator_id' => $transfer->created_by
                    ]);
                }

                return $transfer;
            });
        } catch (\Exception $e) {
            Log::error('Error approving transfer: ' . $e->getMessage(), [
                'transfer_id' => $transfer->id,
                'approver_id' => $approver->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function completeTransfer(Transfer $transfer)
    {
        try {
            return DB::transaction(function () use ($transfer) {
                // Double check status and counts
                $approvedCount = TransferApproval::where('transfer_id', $transfer->id)
                    ->where('status', 'approved')
                    ->count();

                if ($approvedCount < $transfer->approval_count_required) {
                    Log::error('Attempted to complete transfer without all approvals.', [
                        'transfer_id' => $transfer->id,
                        'required' => $transfer->approval_count_required,
                        'current' => $approvedCount
                    ]);
                    throw new \Exception('All approvals required before completion. (Found ' . $approvedCount . ' of ' . $transfer->approval_count_required . ')');
                }

                // Update Employee Office Info
                $officeInfo = EmployeeOfficeInfo::where('employee_id', $transfer->employee_id)->firstOrFail();
                $officeInfo->update([
                    'current_company_id' => $transfer->requested_company_id,
                    'current_business_unit_id' => $transfer->requested_business_unit_id,
                    'current_division_id' => $transfer->requested_division_id,
                    'current_department_id' => $transfer->requested_department_id,
                    'current_section_id' => $transfer->requested_section_id,
                    'current_designation_id' => $transfer->requested_designation_id ?? $officeInfo->current_designation_id,
                ]);

                $transfer->update([
                    'status' => 'completed',
                    'completed_by' => auth()->id(),
                    'completed_at' => now(),
                ]);

                // Notify Employee (System + Laravel)
                try {
                    $employeeUser = User::where('employee_id', $transfer->employee_id)->first();
                    if ($employeeUser) {
                        $employeeUser->notify(new TransferCompletedNotification($transfer));

                        $this->notificationService->createNotification(
                            'Employee',
                            $employeeUser->id,
                            'Transfer Request Completed',
                            'Your transfer request has been fully approved and completed.',
                            ['transfer_id' => $transfer->id]
                        );
                    }
                } catch (\Exception $ne) {
                    Log::warning('Failed to notify employee of completed transfer: ' . $ne->getMessage(), [
                        'transfer_id' => $transfer->id,
                        'employee_id' => $transfer->employee_id
                    ]);
                }

                return $transfer;
            });
        } catch (\Exception $e) {
            Log::error('Error completing transfer: ' . $e->getMessage(), [
                'transfer_id' => $transfer->id,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
