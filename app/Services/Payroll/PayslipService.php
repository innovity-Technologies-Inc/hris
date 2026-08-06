<?php

namespace App\Services\Payroll;

use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Payroll\Payroll;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class PayslipService
{
    /**
     * Generate PDF content for a payslip
     *
     * @param int $payrollId
     * @return string PDF content
     * @throws Exception
     */
    public function generatePayslip(int $payrollId): string
    {
        $payroll = Payroll::with(['getEmployee', 'getBatch'])->findOrFail($payrollId);
        $employee = $payroll->getEmployee;
        
        $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany', 'getCurrentDesignation', 'getCurrentDepartment'])
            ->where('employee_id', $employee->id)
            ->first();

        $html = View::make('payroll.salary.payslip_pdf', compact('payroll', 'employee', 'officeInfo'))->render();

        try {
            return \App\HelperClass::configureBrowsershot(Browsershot::html($html))
                ->setOption('landscape', false)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->pdf();
        } catch (Exception $e) {
            Log::error('[Payslip] PDF generation failed', [
                'payroll_id' => $payrollId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to generate payslip PDF: ' . $e->getMessage());
        }
    }
}

