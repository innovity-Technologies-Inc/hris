<?php

namespace App\Services;

use App\Models\EmployeeOfficeInfo;
use App\Models\Payroll\Payroll;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class SalaryCertificateService
{
    /**
     * Generate PDF content for a salary certificate
     *
     * @param int $payrollId
     * @return string PDF content
     * @throws Exception
     */
    public function generateSalaryCertificate(int $payrollId): string
    {
        $payroll = Payroll::with(['getEmployee', 'getBatch'])->findOrFail($payrollId);
        $employee = $payroll->getEmployee;
        
        $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany', 'getCurrentDesignation', 'getCurrentDepartment'])
            ->where('employee_id', $employee->id)
            ->first();

        $html = View::make('payroll.salary.salary_certificate_pdf', compact('payroll', 'employee', 'officeInfo'))->render();

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
                ->margins(20, 20, 20, 20)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->timeout(config('browsershot.timeout', 60))
                ->pdf();
        } catch (Exception $e) {
            Log::error('[SalaryCertificate] PDF generation failed', [
                'payroll_id' => $payrollId,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to generate salary certificate PDF: ' . $e->getMessage());
        }
    }
}
