<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeOfficeInfo;
use App\Models\EmployeeSalaryBreakdown;
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

        $data = [
            'gross_salary' => $payroll->salary,
            'overtime' => $payroll->overtime_amount,
            'other_allowances' => $payroll->offday_work_salary + $payroll->bonus_amount,
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
            'gross_salary' => $breakdown->gross_salary,
            'overtime' => 0, // Current breakdown doesn't include OT
            'other_allowances' => 0,
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
                ->margins(10, 15, 10, 15) // Slightly smaller margins to help fit one page
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
