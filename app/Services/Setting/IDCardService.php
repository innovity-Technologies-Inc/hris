<?php

namespace App\Services\Setting;

use App\HelperClass;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeId;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Setting\GeneralSetting;
use App\Models\Setting\IDCardDesign;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

/**
 * ID Card Service
 *
 * Handles all ID card generation operations including:
 * - Selecting active design
 * - Rendering HTML templates
 * - Generating PDFs via Browsershot (Chrome-based)
 * - Saving files to storage
 * - Managing employee_ids records
 *
 * @package App\Services
 */
class IDCardService
{
    protected QrCodeService $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Get the currently active ID card design
     *
     * @return IDCardDesign|null
     */
    public function getActiveDesign(): ?IDCardDesign
    {
        return IDCardDesign::where('status', 'active')->first();
    }

    /**
     * Check if there is an active ID card design available
     *
     * @return bool
     */
    public function hasActiveDesign(): bool
    {
        return $this->getActiveDesign() !== null;
    }

    /**
     * Get employee data prepared for ID card template
     *
     * @param Employee $employee
     * @return object
     */
    public function prepareEmployeeData(Employee $employee): object
    {
        $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany', 'getCurrentDesignation', 'getCurrentDepartment'])
            ->where('employee_id', $employee->id)
            ->first();

        $currentCompany = $officeInfo?->getCurrentCompany;
        $currentDesignation = $officeInfo?->getCurrentDesignation;
        $currentDepartment = $officeInfo?->getCurrentDepartment;

        return (object) [
            'id' => $employee->id,
            'employee_id' => $employee->system_id,
            'name' => $employee->full_name,
            'full_name' => $employee->full_name,
            'system_id' => $employee->system_id,
            'designation' => $currentDesignation?->company_designation ?? 'N/A',
            'department' => $currentDepartment?->department_name ?? 'N/A',
            'join_date' => $officeInfo?->date_of_join ? date('d M Y', strtotime($officeInfo->date_of_join)) : 'N/A',
            'blood_group' => $employee->blood_group ?? 'N/A',
            'emergency_contact' => $employee->personal_mobile ?? 'N/A',
            'emergency_contact_name' => $employee->spouse_name ?? ($employee->father_name ?? 'N/A'),
            'email' => $employee->work_email ?? $employee->personal_email ?? 'N/A',
            'photo' => $employee->photo_path,
            'photo_path' => $employee->photo_path,
            'personal_mobile' => $employee->personal_mobile,
            'work_mobile' => $employee->work_mobile,
        ];
    }

    /**
     * Get company data for ID card template
     *
     * @param Employee $employee
     * @return object
     */
    public function prepareCompanyData(Employee $employee): object
    {
        $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany'])
            ->where('employee_id', $employee->id)
            ->first();

        $currentCompany = $officeInfo?->getCurrentCompany;
        $generalSettings = GeneralSetting::first();

        return (object) [
            'name' => $currentCompany?->name ?? $generalSettings?->company_name ?? 'Company Name',
            'logo' => $currentCompany?->logo ?? $generalSettings?->logo_light ?? null,
            'logo_light' => $currentCompany?->logo ?? $generalSettings?->logo_light ?? null,
            'website' => $generalSettings?->website ?? '',
            'contact_phone' => $generalSettings?->contact_phone ?? '',
            'address' => $generalSettings?->address ?? '',
        ];
    }

    /**
     * Render the ID card HTML from the design template
     *
     * @param IDCardDesign $design
     * @param Employee $employee
     * @return string
     * @throws Exception
     */
    public function renderIdCardHtml(IDCardDesign $design, Employee $employee): string
    {
        Log::info('[PDF] Starting ID card HTML rendering', [
            'design_id' => $design->id,
            'design_name' => $design->name,
            'employee_id' => $employee->id,
            'employee_name' => $employee->full_name,
            'design_file_path' => $design->file_path
        ]);

        if (!Storage::disk('public')->exists($design->file_path)) {
            Log::error('[PDF] Design template file not found', [
                'file_path' => $design->file_path,
                'full_path' => Storage::disk('public')->path($design->file_path)
            ]);
            throw new Exception('Design template file not found');
        }

        $fullPath = Storage::disk('public')->path($design->file_path);
        Log::info('[PDF] Design template file found', ['fullPath' => $fullPath]);

        // Create a temporary view file
        $tempViewName = 'id_card_' . uniqid();
        $tempFileName = $tempViewName . '.blade.php';
        $tempPath = resource_path('views/temp/' . $tempFileName);

        // Ensure temp directory exists
        $tempDir = dirname($tempPath);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Copy the uploaded design file to temp views folder
        copy($fullPath, $tempPath);

        try {
            // Prepare data for the template
            $employee = $employee; // Original Employee model

            // Get office info with relationships
            $officeInfo = EmployeeOfficeInfo::with(['getCurrentCompany', 'getCurrentDesignation', 'getCurrentDepartment'])
                ->where('employee_id', $employee->id)
                ->first();

            $currentCompany = $officeInfo?->getCurrentCompany;
            $currentDesignation = $officeInfo?->getCurrentDesignation;
            $currentDepartment = $officeInfo?->getCurrentDepartment;

            // Get general settings
            $generalSettings = GeneralSetting::first();

            // Prepare company info
            $companyInfo = (object) [
                'name' => $currentCompany?->name ?? $generalSettings?->company_name ?? 'Company Name',
                'logo' => $currentCompany?->logo ?? $generalSettings?->logo_light ?? null,
                'website' => $generalSettings?->website ?? '',
                'telephone' => $currentCompany?->telephone ?? ($generalSettings?->contact_phone ?? ''),
                'fax' => $currentCompany?->fax ?? '',
                'email' => $currentCompany?->email ?? ($generalSettings?->email ?? ''),
                'address' => $currentCompany?->address ?? ($generalSettings?->address ?? ''),
                'city' => $generalSettings?->city ?? '',
                'state' => $generalSettings?->state ?? '',
                'zip_code' => $generalSettings?->zip_code ?? '',
                'country' => $generalSettings?->country ?? '',
            ];

            // Render the blade template
            Log::info('[PDF] Rendering blade template', [
                'tempViewName' => $tempViewName,
                'has_employee' => isset($employee),
                'has_officeInfo' => isset($officeInfo),
                'has_generalSettings' => isset($generalSettings)
            ]);

            $html = View::make('temp.' . $tempViewName, compact(
                'employee',
                'officeInfo',
                'currentCompany',
                'currentDesignation',
                'currentDepartment',
                'companyInfo',
                'generalSettings'
            ))->render();

            Log::info('[PDF] Blade template rendered successfully', [
                'html_length' => strlen($html),
                'contains_img_tag' => strpos($html, '<img') !== false,
                'contains_qr' => strpos($html, 'qrCode') !== false || strpos($html, 'data:image/png;base64') !== false
            ]);

            return $html;

        } finally {
            // Clean up temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }
    }

    /**
     * Generate PDF from the ID card design for an employee
     *
     * @param Employee $employee
     * @param IDCardDesign|null $design Optional design, uses active if not provided
     * @return string PDF content as string
     * @throws Exception
     */
    public function generatePdfContent(Employee $employee, ?IDCardDesign $design = null): string
    {
        Log::info('[PDF] Starting PDF generation', [
            'employee_id' => $employee->id,
            'employee_name' => $employee->full_name,
            'design_provided' => $design !== null
        ]);

        $design = $design ?? $this->getActiveDesign();

        if (!$design) {
            Log::error('[PDF] No active ID card design available');
            throw new Exception('No active ID card design available');
        }

        Log::info('[PDF] Using design', ['design_id' => $design->id, 'design_name' => $design->name]);

        $html = $this->renderIdCardHtml($design, $employee);
        Log::info('[PDF] HTML rendered, length: ' . strlen($html));

        try {
            // Create a temporary HTML file
            $tempHtmlPath = storage_path('app/temp/' . uniqid('idcard_') . '.html');
            $tempDir = dirname($tempHtmlPath);

            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            file_put_contents($tempHtmlPath, $html);
            Log::info('[PDF] Temporary HTML file created', [
                'tempHtmlPath' => $tempHtmlPath,
                'file_size' => filesize($tempHtmlPath)
            ]);

            // Generate PDF using Browsershot
            Log::info('[PDF] Starting Browsershot PDF generation', [
                'node_binary' => config('browsershot.node_binary', 'node'),
                'npm_binary' => config('browsershot.npm_binary', 'npm'),
                'node_modules_path' => config('browsershot.node_modules_path', base_path('node_modules')),
                'timeout' => config('browsershot.timeout', 60)
            ]);

            $pdfContent = Browsershot::html($html)
                ->setNodeBinary(config('browsershot.node_binary', 'node'))
                ->setNpmBinary(config('browsershot.npm_binary', 'npm'))
                ->setNodeModulePath(config('browsershot.node_modules_path', base_path('node_modules')))
                ->addChromiumArguments(config('browsershot.chrome_arguments', [
                    '--disable-gpu',
                    '--no-sandbox',
                    '--disable-dev-shm-usage',
                ]))
                ->setOption('landscape', false)
                ->paperSize(210, 297) // A4 in millimeters
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->timeout(config('browsershot.timeout', 60))
                ->pdf();

            Log::info('[PDF] PDF generated successfully', [
                'pdf_size_bytes' => strlen($pdfContent)
            ]);

            // Clean up temporary file
            if (file_exists($tempHtmlPath)) {
                unlink($tempHtmlPath);
            }

            return $pdfContent;

        } catch (\Exception $e) {
            // Clean up temporary file on error
            if (isset($tempHtmlPath) && file_exists($tempHtmlPath)) {
                unlink($tempHtmlPath);
            }

            Log::error('[PDF] Browsershot PDF generation failed', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);

            throw new Exception('Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate and save PDF to storage
     *
     * @param Employee $employee
     * @param IDCardDesign|null $design
     * @return string Path to saved PDF file
     * @throws Exception
     */
    public function generateAndSavePdf(Employee $employee, ?IDCardDesign $design = null): string
    {
        $pdfContent = $this->generatePdfContent($employee, $design);

        // Generate unique filename using employee ID and timestamp
        $fileName = sprintf(
            'employee_%s_%s.pdf',
            $employee->system_id ?? $employee->id,
            date('Ymd_His')
        );

        $filePath = 'employee_id_cards/' . $fileName;

        // Ensure directory exists and save file
        Storage::disk('public')->put($filePath, $pdfContent);

        return $filePath;
    }

    /**
     * Generate ID card for an employee and create/update the employee_ids record
     *
     * @param Employee $employee
     * @param int|null $validityYears Number of years the card is valid (default: 2)
     * @return EmployeeId
     * @throws Exception
     */
    public function generateIdCard(Employee $employee, ?int $validityYears = 2): EmployeeId
    {
        $design = $this->getActiveDesign();

        if (!$design) {
            throw new Exception('No active ID card design available. Please activate a design first.');
        }

        try {
            DB::beginTransaction();

            // Delete any existing inactive ID cards for this employee to avoid unique constraint violation
            $oldInactiveCards = EmployeeId::where('employee_id', $employee->id)
                ->where('status', 'inactive')
                ->get();

            foreach ($oldInactiveCards as $oldCard) {
                if ($oldCard->pdf_path && Storage::disk('public')->exists($oldCard->pdf_path)) {
                    Storage::disk('public')->delete($oldCard->pdf_path);
                }
                $oldCard->delete();
            }

            // Deactivate any existing active ID cards for this employee
            EmployeeId::where('employee_id', $employee->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']);

            // Generate and save the PDF
            $pdfPath = $this->generateAndSavePdf($employee, $design);

            // Generate card number
            $cardNumber = $this->generateCardNumber($employee);

            // Create new employee ID record
            $employeeId = EmployeeId::create([
                'employee_id' => $employee->id,
                'id_card_design_id' => $design->id,
                'status' => 'active',
                'pdf_path' => $pdfPath,
                'card_number' => $cardNumber,
                'issue_date' => now()->toDateString(),
                'expiry_date' => now()->addYears($validityYears)->toDateString(),
            ]);

            DB::commit();

            Log::info('ID Card generated successfully', [
                'employee_id' => $employee->id,
                'employee_id_record' => $employeeId->id,
                'pdf_path' => $pdfPath,
            ]);

            return $employeeId;

        } catch (Exception $e) {
            DB::rollBack();

            // Clean up PDF file if it was created
            if (isset($pdfPath) && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }

            Log::error('ID Card generation failed', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
            ]);

            throw new Exception('Failed to generate ID card: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique card number for an employee
     *
     * @param Employee $employee
     * @return string
     */
    protected function generateCardNumber(Employee $employee): string
    {
        $prefix = 'IDC';
        $year = date('Y');
        $employeeId = str_pad($employee->id, 6, '0', STR_PAD_LEFT);
        $random = strtoupper(Str::random(4));

        return sprintf('%s-%s-%s-%s', $prefix, $year, $employeeId, $random);
    }

    /**
     * Regenerate ID card for an employee (invalidates old card)
     *
     * @param Employee $employee
     * @param int|null $validityYears
     * @return EmployeeId
     * @throws Exception
     */
    public function regenerateIdCard(Employee $employee, ?int $validityYears = 2): EmployeeId
    {
        // Delete old PDF files for this employee
        $oldCards = EmployeeId::where('employee_id', $employee->id)->get();

        foreach ($oldCards as $oldCard) {
            if ($oldCard->pdf_path && Storage::disk('public')->exists($oldCard->pdf_path)) {
                Storage::disk('public')->delete($oldCard->pdf_path);
            }
        }

        return $this->generateIdCard($employee, $validityYears);
    }

    /**
     * Get the active ID card for an employee
     *
     * @param Employee $employee
     * @return EmployeeId|null
     */
    public function getActiveIdCard(Employee $employee): ?EmployeeId
    {
        return EmployeeId::where('employee_id', $employee->id)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Check if employee has an active ID card
     *
     * @param Employee $employee
     * @return bool
     */
    public function hasActiveIdCard(Employee $employee): bool
    {
        return $this->getActiveIdCard($employee) !== null;
    }

    /**
     * Deactivate an employee's ID card
     *
     * @param Employee $employee
     * @return bool
     */
    public function deactivateIdCard(Employee $employee): bool
    {
        try {
            DB::beginTransaction();

            // Delete any existing inactive ID cards for this employee to prevent unique constraint violation
            $oldInactiveCards = EmployeeId::where('employee_id', $employee->id)
                ->where('status', 'inactive')
                ->get();

            foreach ($oldInactiveCards as $oldCard) {
                if ($oldCard->pdf_path && Storage::disk('public')->exists($oldCard->pdf_path)) {
                    Storage::disk('public')->delete($oldCard->pdf_path);
                }
                $oldCard->delete();
            }

            // Deactivate the active card
            $updated = EmployeeId::where('employee_id', $employee->id)
                ->where('status', 'active')
                ->update(['status' => 'inactive']) > 0;

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to deactivate ID card', ['employee_id' => $employee->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get PDF file path for viewing/downloading
     *
     * @param EmployeeId $employeeId
     * @return string|null
     */
    public function getPdfPath(EmployeeId $employeeId): ?string
    {
        if ($employeeId->pdfExists()) {
            return $employeeId->getFullPdfPath();
        }
        return null;
    }

    /**
     * Stream PDF for browser viewing
     *
     * @param EmployeeId $employeeId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|null
     */
    public function streamPdf(EmployeeId $employeeId)
    {
        if (!$employeeId->pdfExists()) {
            return null;
        }

        return Storage::disk('public')->response(
            $employeeId->pdf_path,
            basename($employeeId->pdf_path),
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Download PDF
     *
     * @param EmployeeId $employeeId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|null
     */
    public function downloadPdf(EmployeeId $employeeId)
    {
        if (!$employeeId->pdfExists()) {
            return null;
        }

        return Storage::disk('public')->download(
            $employeeId->pdf_path,
            'ID_Card_' . ($employeeId->card_number ?? $employeeId->id) . '.pdf'
        );
    }

    /**
     * Validate design file for security issues
     *
     * @param string $fileContent
     * @return array Returns ['valid' => bool, 'error' => string|null]
     */
    public function validateDesignFile(string $fileContent): array
    {
        $dangerousFunctions = [
            'eval', 'exec', 'system', 'shell_exec', 'passthru',
            'proc_open', 'popen', 'curl_exec', 'curl_multi_exec',
            'parse_ini_file', 'show_source', 'file_put_contents'
        ];

        foreach ($dangerousFunctions as $func) {
            if (stripos($fileContent, $func) !== false) {
                return [
                    'valid' => false,
                    'error' => "Security violation: Dangerous function '{$func}' detected in template"
                ];
            }
        }

        return ['valid' => true, 'error' => null];
    }
}

