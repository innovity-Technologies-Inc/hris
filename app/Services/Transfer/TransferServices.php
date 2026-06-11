<?php

namespace App\Services\Transfer;

use App\Enums\UserType;
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

    public function getTransferList(array $filters)
    {
        $user = auth()->user();
        
        $query = Transfer::withoutGlobalScopes()
            ->with(['employee', 'requestedCompany', 'requestedBusinessUnit']);

        // 1. Apply Scoping / Permissions manually since we bypassed global scopes
        if ($user->user_type !== UserType::Group) {
            $query->where(function($q) use ($user) {
                // Own scope
                $q->where(function($sq) use ($user) {
                    $employee = $user->employee()->with('officeInfo')->first();
                    if ($employee && $employee->officeInfo) {
                        $office = $employee->officeInfo;
                        if ($user->user_type === UserType::Company) $sq->where('current_company_id', $office->current_company_id);
                        elseif ($user->user_type === UserType::BusinessUnit) $sq->where('current_business_unit_id', $office->current_business_unit_id);
                        elseif ($user->user_type === UserType::Division) $sq->where('current_division_id', $office->current_division_id);
                        elseif ($user->user_type === UserType::Department) $sq->where('current_department_id', $office->current_department_id);
                        elseif ($user->user_type === UserType::Section) $sq->where('current_section_id', $office->current_section_id);
                    }
                    if ($user->user_type === UserType::Employee) {
                        $sq->orWhere('employee_id', $user->employee_id);
                    }
                });

                // OR Assigned Approver
                $q->orWhereHas('approvals', function($aq) use ($user) {
                    $aq->where('approver_id', $user->id);
                });
                
                // OR Creator
                $q->orWhere('created_by', $user->id);
            });
        }

        // 2. Advanced Filters
        if (!empty($filters['employee_search'])) {
            $search = $filters['employee_search'];
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('applicant_id', 'like', "%{$search}%")
                  ->orWhere('system_id', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['requested_company_id'])) $query->where('requested_company_id', $filters['requested_company_id']);
        if (!empty($filters['requested_business_unit_id'])) $query->where('requested_business_unit_id', $filters['requested_business_unit_id']);
        if (!empty($filters['requested_division_id'])) $query->where('requested_division_id', $filters['requested_division_id']);
        if (!empty($filters['requested_department_id'])) $query->where('requested_department_id', $filters['requested_department_id']);
        if (!empty($filters['requested_section_id'])) $query->where('requested_section_id', $filters['requested_section_id']);

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function searchAuthorities(array $filters)
    {
        $query = User::permission('transfers.approve')
            ->with(['employee.officeInfo' => function($q) {
                $q->withoutGlobalScopes();
            }, 'employee.officeInfo.getCurrentCompany', 'employee.officeInfo.getCurrentBusinessUnit']);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        
        if (!empty($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (!empty($filters['company_id']) || !empty($filters['unit_id']) || !empty($filters['division_id']) || !empty($filters['department_id']) || !empty($filters['section_id'])) {
            $query->whereHas('employee.officeInfo', function($q) use ($filters) {
                $q->withoutGlobalScopes();
                if (!empty($filters['company_id'])) $q->where('current_company_id', $filters['company_id']);
                if (!empty($filters['unit_id'])) $q->where('current_business_unit_id', $filters['unit_id']);
                if (!empty($filters['division_id'])) $q->where('current_division_id', $filters['division_id']);
                if (!empty($filters['department_id'])) $q->where('current_department_id', $filters['department_id']);
                if (!empty($filters['section_id'])) $q->where('current_section_id', $filters['section_id']);
            });
        }

        return $query->limit(30)->get();
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
                        // 1. System Notification (Always save to DB)
                        try {
                            $this->notificationService->createNotification(
                                $approver->user_type->value,
                                $approver->id,
                                'New Transfer Approval Required',
                                'You have a pending transfer approval for ' . $transfer->employee->full_name,
                                ['transfer_id' => $transfer->id]
                            );
                        } catch (\Exception $e) {
                            Log::error('System notification failed for approver: ' . $e->getMessage());
                        }

                        // 2. Laravel Notification (May fail due to Mail)
                        try {
                            $approver->notify(new TransferRequestedNotification($transfer));
                        } catch (\Exception $ne) {
                            Log::warning('Laravel mail notification failed for approver: ' . $ne->getMessage(), [
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
                // Use withoutGlobalScopes to find the approval record
                $approval = TransferApproval::where('transfer_id', $transfer->id)
                    ->where('approver_id', $approver->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($approval->status !== 'pending') {
                    throw new \Exception('This approval request has already been processed.');
                }

                $approval->update([
                    'status' => 'approved',
                    'remarks' => $remarks,
                    'approved_at' => now(),
                ]);

                // Recalculate count bypassing all scopes to ensure accuracy
                $approvedCount = TransferApproval::where('transfer_id', $transfer->id)
                    ->where('status', 'approved')
                    ->count();

                $transfer->withoutGlobalScopes()->update([
                    'current_approval_count' => $approvedCount
                ]);

                if ($approvedCount >= $transfer->approval_count_required) {
                    $transfer->withoutGlobalScopes()->update(['status' => 'approved']);
                    Log::info('Transfer request fully approved.', ['transfer_id' => $transfer->id]);
                }

                // Notify Creator (System + Laravel)
                try {
                    $creator = User::find($transfer->created_by);
                    if ($creator) {
                        // 1. System Notification
                        try {
                            $this->notificationService->createNotification(
                                $creator->user_type->value,
                                $creator->id,
                                'Transfer Request Update',
                                'An authority has approved your transfer request for ' . $transfer->employee->full_name,
                                ['transfer_id' => $transfer->id]
                            );
                        } catch (\Exception $e) {
                            Log::error('System notification failed for creator: ' . $e->getMessage());
                        }

                        // 2. Laravel Notification
                        try {
                            $creator->notify(new TransferApprovedNotification($transfer));
                        } catch (\Exception $ne) {
                            Log::warning('Laravel mail notification failed for creator: ' . $ne->getMessage());
                        }
                    }
                } catch (\Exception $ge) {
                    Log::error('General notification error for creator: ' . $ge->getMessage());
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
                // Double check status and counts bypassing all scopes
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
                $officeInfo = EmployeeOfficeInfo::withoutGlobalScopes()->where('employee_id', $transfer->employee_id)->firstOrFail();
                $officeInfo->update([
                    'current_company_id' => $transfer->requested_company_id,
                    'current_business_unit_id' => $transfer->requested_business_unit_id,
                    'current_division_id' => $transfer->requested_division_id,
                    'current_department_id' => $transfer->requested_department_id,
                    'current_section_id' => $transfer->requested_section_id,
                    'current_designation_id' => $transfer->requested_designation_id ?? $officeInfo->current_designation_id,
                ]);

                $transfer->withoutGlobalScopes()->update([
                    'status' => 'completed',
                    'completed_by' => auth()->id(),
                    'completed_at' => now(),
                ]);

                // Notify Employee (System + Laravel)
                try {
                    $employeeUser = User::where('employee_id', $transfer->employee_id)->first();
                    if ($employeeUser) {
                        // 1. System Notification
                        try {
                            $this->notificationService->createNotification(
                                UserType::Employee->value,
                                $employeeUser->id,
                                'Transfer Request Completed',
                                'Your transfer request has been fully approved and completed.',
                                ['transfer_id' => $transfer->id]
                            );
                        } catch (\Exception $e) {
                            Log::error('System notification failed for employee: ' . $e->getMessage());
                        }

                        // 2. Laravel Notification
                        try {
                            $employeeUser->notify(new TransferCompletedNotification($transfer));
                        } catch (\Exception $ne) {
                            Log::warning('Laravel mail notification failed for employee: ' . $ne->getMessage());
                        }
                    }
                } catch (\Exception $ge) {
                    Log::error('General notification error for employee: ' . $ge->getMessage());
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
