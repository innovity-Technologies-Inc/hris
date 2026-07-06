<?php

namespace App\Listeners\Workflow;

use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use App\Models\Employee\ProfileUpdateRequest;
use App\Models\Employee\EmployeeEducationExperienceTraining;
use App\Models\Employee\EmployeeEmploymentHistory;
use App\Models\Employee\EmployeeNominee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Employee\EmployeeBankAccount;
use App\Services\Employee\EmployeeServices;
use Illuminate\Http\Request;

class ProfileUpdateWorkflowListener
{
    public function handleCompleted(ApprovalCompleted $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof ProfileUpdateRequest) {
            $approvable->update([
                'status' => 'approved'
            ]);

            $employee = $approvable->employee;
            if ($employee) {
                $section = $approvable->section;
                $reqData = $approvable->requested_data ?? [];
                $reqData['employee_id'] = $approvable->employee_id;

                $employeeServices = app(EmployeeServices::class);

                if ($section === 'general') {
                    $employee->update($reqData);
                } elseif ($section === 'education') {
                    EmployeeEducationExperienceTraining::updateOrCreate(
                        ['employee_id' => $employee->id],
                        [
                            'educations' => $reqData['educations'] ?? ($employee->educationInfo?->educations ?? []),
                            'trainings' => $reqData['trainings'] ?? ($employee->educationInfo?->trainings ?? []),
                            'status' => 'active'
                        ]
                    );
                } elseif ($section === 'employment_history') {
                    EmployeeEmploymentHistory::updateOrCreate(
                        ['employee_id' => $employee->id],
                        [
                            'histories' => $reqData['histories'] ?? [],
                            'status' => 'active'
                        ]
                    );
                } elseif ($section === 'emergency_contact') {
                    EmployeeNominee::updateOrCreate(
                        ['employee_id' => $employee->id],
                        array_merge($reqData, ['status' => 'active'])
                    );
                } elseif ($section === 'office-information') {
                    $employeeOfficeInfo = EmployeeOfficeInfo::where('employee_id', $employee->id)->first();
                    $req = new Request();
                    $req->replace($reqData);
                    $employeeServices->employeeOfficeInfoSave($req, $reqData, $employeeOfficeInfo);
                } elseif ($section === 'employee-policy') {
                    $employeePlan = EmployeeEligiblePlan::where('employee_id', $employee->id)->first();
                    $employeeServices->employeeEligiblePanInfoSave($reqData, $employeePlan);
                } elseif ($section === 'salary-breakdown') {
                    $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
                    $employeeServices->employeeSalaryBreakdownInfoSave($reqData, $employeeSalary);
                } elseif ($section === 'employee-bank-account') {
                    $employeeBank = EmployeeBankAccount::where('employee_id', $employee->id)->first();
                    $employeeServices->employeeBankAccountsInfoSave($reqData, $employeeBank);
                }
            }
        }
    }

    public function handleRejected(ApprovalRejected $event): void
    {
        $approvable = $event->approvalRequest->approvable;

        if ($approvable instanceof ProfileUpdateRequest) {
            $approvable->update([
                'status' => 'rejected'
            ]);
        }
    }
}
