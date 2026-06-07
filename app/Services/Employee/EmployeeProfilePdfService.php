<?php

namespace App\Services\Employee;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Setting\GeneralSetting;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class EmployeeProfilePdfService
{
    /**
     * Generate PDF content for detailed employee profile
     *
     * @param int $employeeId
     * @return string PDF content
     * @throws Exception
     */
    public function generateDetailedProfilePdf(int $employeeId): string
    {
        $employee = Employee::with([
                'officeInfo.getCurrentCompany', 
                'officeInfo.getCurrentBusinessUnit', 
                'officeInfo.getCurrentDivision', 
                'officeInfo.getCurrentDepartment', 
                'officeInfo.getCurrentSection', 
                'officeInfo.getCurrentDesignation', 
                'officeInfo.getJoiningCompany', 
                'officeInfo.getJoiningBusinessUnit', 
                'officeInfo.getJoiningDivision', 
                'officeInfo.getJoiningDepartment', 
                'officeInfo.getJoiningSection', 
                'officeInfo.getJoiningDesignation', 
                'officeInfo.getGrade',
                'educationInfo',
                'employmentHistory',
                'nomineeInfo',
                'employeeEligibility',
                'salaryBreakdown',
                'bankAccount.getBank',
                'bankAccount.getBranch',
                'shift',
                'roster',
                'offDayPlan',
                'leaveApplications.getPlan',
                'leaveBalances'
            ])->findOrFail($employeeId);
        
        $officeInfo = $employee->officeInfo;

        $generalSettings = GeneralSetting::first();
        $currentCompany = $officeInfo?->getCurrentCompany;

        $companyInfo = (object) [
            'name' => $currentCompany?->name ?? $generalSettings?->company_name ?? 'Company Name',
            'logo' => $currentCompany?->logo ?? $generalSettings?->logo_light ?? null,
            'address' => $currentCompany?->address ?? ($generalSettings?->address ?? ''),
            'email' => $currentCompany?->email ?? ($generalSettings?->email ?? ''),
            'phone' => $currentCompany?->telephone ?? ($generalSettings?->contact_phone ?? ''),
        ];

        $html = View::make('employee.profile_pdf', compact('employee', 'officeInfo', 'companyInfo'))->render();

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
                ->paperSize(210, 297) // A4
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->timeout(config('browsershot.timeout', 60))
                ->pdf();
        } catch (Exception $e) {
            Log::error('Browsershot PDF generation failed: ' . $e->getMessage());
            throw new Exception('PDF generation failed: ' . $e->getMessage());
        }
    }
}
