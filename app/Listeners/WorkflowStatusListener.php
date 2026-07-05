<?php

namespace App\Listeners;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Payroll\Promotion;
use App\Models\Payroll\Increment;
use Illuminate\Support\Facades\Log;

class WorkflowStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the completed workflow.
     */
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $request = $event->approvalRequest;
        $approvable = $request->approvable; // The model instance

        Log::info("Workflow completed for module: " . $request->workflow->module);

        if ($request->workflow->module === 'promotion') {
            if ($approvable instanceof Promotion) {
                $approvable->update([
                    'status' => 'approved',
                    // Optional: 'is_adjustment' => 1 can be done here or in the cron job
                ]);
            }
        }

        if ($request->workflow->module === 'increment') {
            if ($approvable instanceof Increment) {
                $approvable->update([
                    'status' => 'approved',
                ]);
            }
        }

        if ($request->workflow->module === 'leave') {
            if ($approvable instanceof \App\Models\Leave\Leave) {
                $approvable->update([
                    'status' => 'approved',
                ]);

                $leaveCount = \App\Models\Leave\LeaveCount::where('employee_id', $approvable->employee_id)
                    ->where('plan_id', $approvable->plan_id)
                    ->first();

                if ($leaveCount) {
                    $leaveCount->increment('leave_taken', $approvable->leave_count);
                } else {
                    \App\Models\Leave\LeaveCount::create([
                        'employee_id' => $approvable->employee_id,
                        'plan_id' => $approvable->plan_id,
                        'leave_taken' => $approvable->leave_count
                    ]);
                }
            }
        }

        if (in_array($request->workflow->module, ['salary', 'bonus'])) {
            if ($approvable instanceof \App\Models\Payroll\PayrollProcess) {
                $approvable->update([
                    'approval_status' => 'approved',
                    'status' => 'approved'
                ]);
            }
        }

        if (in_array($request->workflow->module, ['profile-update', 'office-information', 'employee-policy', 'salary-breakdown', 'employee-bank-account'])) {
            if ($approvable instanceof \App\Models\Employee\ProfileUpdateRequest) {
                $approvable->update([
                    'status' => 'approved'
                ]);

                $employee = $approvable->employee;
                if ($employee) {
                    $section = $approvable->section;
                    $reqData = $approvable->requested_data ?? [];
                    $reqData['employee_id'] = $approvable->employee_id;

                    $employeeServices = app(\App\Services\Employee\EmployeeServices::class);

                    if ($section === 'general') {
                        $employee->update($reqData);
                    } elseif ($section === 'education') {
                        \App\Models\Employee\EmployeeEducationExperienceTraining::updateOrCreate(
                            ['employee_id' => $employee->id],
                            [
                                'educations' => $reqData['educations'] ?? ($employee->educationInfo?->educations ?? []),
                                'trainings' => $reqData['trainings'] ?? ($employee->educationInfo?->trainings ?? []),
                                'status' => 'active'
                            ]
                        );
                    } elseif ($section === 'employment_history') {
                        \App\Models\Employee\EmployeeEmploymentHistory::updateOrCreate(
                            ['employee_id' => $employee->id],
                            [
                                'histories' => $reqData['histories'] ?? [],
                                'status' => 'active'
                            ]
                        );
                    } elseif ($section === 'emergency_contact') {
                        \App\Models\Employee\EmployeeNominee::updateOrCreate(
                            ['employee_id' => $employee->id],
                            array_merge($reqData, ['status' => 'active'])
                        );
                    } elseif ($section === 'office-information') {
                        $employeeOfficeInfo = \App\Models\Employee\EmployeeOfficeInfo::where('employee_id', $employee->id)->first();
                        $req = new \Illuminate\Http\Request();
                        $req->replace($reqData);
                        $employeeServices->employeeOfficeInfoSave($req, $reqData, $employeeOfficeInfo);
                    } elseif ($section === 'employee-policy') {
                        $employeePlan = \App\Models\Employee\EmployeeEligiblePlan::where('employee_id', $employee->id)->first();
                        $employeeServices->employeeEligiblePanInfoSave($reqData, $employeePlan);
                    } elseif ($section === 'salary-breakdown') {
                        $employeeSalary = \App\Models\Employee\EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
                        $employeeServices->employeeSalaryBreakdownInfoSave($reqData, $employeeSalary);
                    } elseif ($section === 'employee-bank-account') {
                        $employeeBank = \App\Models\Employee\EmployeeBankAccount::where('employee_id', $employee->id)->first();
                        $employeeServices->employeeBankAccountsInfoSave($reqData, $employeeBank);
                    }
                }
            }
        }
    }

    /**
     * Handle the rejected workflow.
     */
    public function handleRejected(ApprovalRejected $event): void
    {
        $request = $event->approvalRequest;
        $approvable = $request->approvable; // The model instance

        Log::info("Workflow rejected for module: " . $request->workflow->module);

        if ($request->workflow->module === 'promotion') {
            if ($approvable instanceof Promotion) {
                $approvable->update([
                    'status' => 'rejected'
                ]);
            }
        }

        if ($request->workflow->module === 'increment') {
            if ($approvable instanceof Increment) {
                $approvable->update([
                    'status' => 'rejected'
                ]);
            }
        }

        if ($request->workflow->module === 'leave') {
            if ($approvable instanceof \App\Models\Leave\Leave) {
                $approvable->update([
                    'status' => 'rejected'
                ]);
            }
        }

        if (in_array($request->workflow->module, ['salary', 'bonus'])) {
            if ($approvable instanceof \App\Models\Payroll\PayrollProcess) {
                $approvable->update([
                    'approval_status' => 'rejected',
                    'status' => 'rejected'
                ]);
            }
        }

        if (in_array($request->workflow->module, ['profile-update', 'office-information', 'employee-policy', 'salary-breakdown', 'employee-bank-account'])) {
            if ($approvable instanceof \App\Models\Employee\ProfileUpdateRequest) {
                $approvable->update([
                    'status' => 'rejected'
                ]);
            }
        }
    }
}
