<?php

namespace App\Services\Payroll;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Payroll\Payroll;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class SalaryCertificateService
{
    /**
     * Generate PDF content for a salary certificate from a specific payroll record
     *
     * @param int $payrollId
     * @return string PDF content
     * @throws Exception
     */
    public function generateSalaryCertificateFromPayroll(int $payrollId): string
    {
        $payroll = Payroll::with(['getEmployee', 'getBatch'])->findOrFail($payrollId);
        $employee = $payroll->getEmployee;
        
        $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany', 'getCurrentDesignation', 'getCurrentDepartment'])
            ->where('employee_id', $employee->id)
            ->first();

        // Get the breakdown related to this employee to show the composition
        $breakdown = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();

        $data = [
            'basic_salary' => $breakdown->basic_salary ?? ($payroll->salary * 0.6), // Fallback to 60% if no breakdown
            'house_allowance' => $breakdown->house_allowance ?? ($payroll->salary * 0.2),
            'transport_allowance' => $breakdown->transport_allowance ?? ($payroll->salary * 0.1),
            'food_allowance' => $breakdown->food_allowance ?? 0,
            'medical_allowance' => $breakdown->medical_allowance ?? ($payroll->salary * 0.1),
            'other_earnings' => ($breakdown->other_earnings ?? 0) + $payroll->offday_work_salary + $payroll->bonus_amount,
            'overtime' => $payroll->overtime_amount,
            'total_remuneration' => $payroll->salary + $payroll->overtime_amount + $payroll->offday_work_salary + $payroll->bonus_amount,
        ];

        return $this->renderPdf($employee, $officeInfo, $data);
    }

    /**
     * Generate PDF content for a salary certificate from current employee breakdown
     *
     * @param int $employeeId
     * @return string PDF content
     * @throws Exception
     */
    public function generateSalaryCertificateFromEmployee(int $employeeId): string
    {
        $employee = Employee::findOrFail($employeeId);
        $breakdown = EmployeeSalaryBreakdown::where('employee_id', $employeeId)->firstOrFail();
        
        $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany', 'getCurrentDesignation', 'getCurrentDepartment'])
            ->where('employee_id', $employeeId)
            ->first();

        $data = [
            'basic_salary' => $breakdown->basic_salary,
            'house_allowance' => $breakdown->house_allowance,
            'transport_allowance' => $breakdown->transport_allowance,
            'food_allowance' => $breakdown->food_allowance,
            'medical_allowance' => $breakdown->medical_allowance,
            'other_earnings' => $breakdown->other_earnings,
            'overtime' => 0,
            'total_remuneration' => $breakdown->gross_salary,
        ];

        return $this->renderPdf($employee, $officeInfo, $data);
    }

    /**
     * Common method to render the PDF
     */
    protected function renderPdf($employee, $officeInfo, $data): string
    {
        $html = View::make('payroll.salary.salary_certificate_pdf', compact('employee', 'officeInfo', 'data'))->render();

        try {
            return Browsershot::html($html)
                ->setNodeBinary(config('browsershot.node_binary', 'node'))
                ->setNpmBinary(config('browsershot.npm_binary', 'npm'))
                ->setNodeModulePath(config('browsershot.node_modules_path', base_path('node_modules')))
                ->addChromiumArguments(config('browsershot.chrome_arguments', [
                    '--disable-gpu',
                    '--no-sandbox',
                    '--disable-dev-shm-usage',
                ]))
                ->setOption('landscape', false)
                ->format('A4')
                ->margins(10, 15, 10, 15)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->timeout(config('browsershot.timeout', 60))
                ->pdf();
        } catch (Exception $e) {
            Log::error('[SalaryCertificate] PDF generation failed', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to generate salary certificate PDF: ' . $e->getMessage());
        }
    }
}

