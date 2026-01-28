# QR Code Module Implementation Guide

## Overview

The QR Code Module provides a comprehensive, server-side QR code generation system for the HRMS project. It enables easy creation and embedding of QR codes in ID cards, attendance verification, document authentication, and access control systems.

**Key Features:**

- ✅ Server-side generation (no JavaScript dependencies)
- ✅ Automatic company logo embedding with transparency control
- ✅ Base64 encoding for direct HTML/PDF embedding
- ✅ File download support
- ✅ Customizable size and margin parameters
- ✅ Built-in cleanup of temporary files
- ✅ Specialized methods for Employee ID Cards, Attendance, and Verification URLs
- ✅ Comprehensive error handling and logging

---

## Installation & Dependencies

### 1. Required Package

The module uses the Endroid QR Code library:

```bash
composer require endroid/qr-code
```

### 2. Directory Permissions

Ensure storage directory is writable for temporary logo processing:

```bash
chmod -R 775 storage/app/temp
```

### 3. General Settings Configuration

The system automatically retrieves your company logo from **General Settings**:

```
Settings → General Settings → Logo (Light) or Logo (Dark)
```

The logo will be:

- Automatically embedded in generated QR codes
- Reduced to 60x60 pixels to maintain scanability
- Processed with opacity for optimal visibility
- Cached temporarily for performance

---

## Service Location

**File Path:** `app/Services/QrCodeService.php`

**Namespace:** `App\Services\QrCodeService`

**Access:** Through Laravel Service Container or direct instantiation

---

## Configuration

### Parameters

All QR generation methods accept these optional parameters:

| Parameter  | Type         | Default   | Range    | Description                                                                    |
| ---------- | ------------ | --------- | -------- | ------------------------------------------------------------------------------ |
| `text`     | string       | -         | -        | Data to encode in QR code                                                      |
| `logoPath` | string\|null | null      | -        | Logo file path: `null` = system logo, `''` = no logo, `/path/to/file` = custom |
| `size`     | int          | 400       | 100-1000 | QR code size in pixels                                                         |
| `margin`   | int          | 10        | 0-50     | White border margin in pixels                                                  |
| `filename` | string\|null | timestamp | -        | Custom filename for downloads                                                  |

### Usage Examples

```php
// With system logo (from General Settings)
$qrCode = $qrCodeService->generateQRBase64('Employee #12345');

// Without logo
$qrCode = $qrCodeService->generateQRBase64('Employee #12345', '');

// Custom size and margin
$qrCode = $qrCodeService->generateQRBase64('Employee #12345', null, 500, 15);

// Custom logo
$qrCode = $qrCodeService->generateQRBase64('Employee #12345', '/path/to/logo.png', 300, 10);
```

---

## API Reference

### 1. Generate QR Code (PNG Binary)

```php
/**
 * Generate QR code and return as PNG binary string
 *
 * @param string $text Data to encode
 * @param string|null $logoPath Logo path
 * @param int $size QR size in pixels (default: 400)
 * @param int $margin Margin in pixels (default: 10)
 * @return string PNG binary data
 * @throws Exception
 */
$qrCodeService->generateQR($text, $logoPath, $size, $margin);
```

**Returns:** Binary PNG image data

**Use Case:** Saving to file, streaming response, direct file operations

**Example:**

```php
$qrPng = $qrCodeService->generateQR('Employee Data');
file_put_contents('qrcode.png', $qrPng);
```

---

### 2. Generate QR Code (Base64)

```php
/**
 * Generate QR code and return as base64 data URL
 *
 * @param string $text Data to encode
 * @param string|null $logoPath Logo path
 * @param int $size QR size in pixels (default: 400)
 * @param int $margin Margin in pixels (default: 10)
 * @return string Base64 data URL
 * @throws Exception
 */
$qrCodeService->generateQRBase64($text, $logoPath, $size, $margin);
```

**Returns:** `data:image/png;base64,iVBORw0KGgo...`

**Use Case:** Embedding in HTML img tags, PDF generation, Blade templates

**Example:**

```php
// In Blade template
<img src="{{ $qrCode }}" alt="QR Code" style="width: 200px;">

// For PDF
<img src="{{ $qrBase64 }}" />
```

---

### 3. Download QR Code

```php
/**
 * Prepare QR code for file download
 *
 * @param string $text Data to encode
 * @param string|null $logoPath Logo path
 * @param string|null $filename Custom filename
 * @param int $size QR size in pixels (default: 400)
 * @param int $margin Margin in pixels (default: 10)
 * @return array ['data' => PNG binary, 'filename' => string]
 * @throws Exception
 */
$qrCodeService->downloadQR($text, $logoPath, $filename, $size, $margin);
```

**Returns:** Array with 'data' (binary) and 'filename' (string)

**Use Case:** Direct file downloads, file streaming

**Example:**

```php
$qr = $qrCodeService->downloadQR('Employee Data', null, 'employee_qr');

return response($qr['data'], 200)
    ->header('Content-Type', 'image/png')
    ->header('Content-Disposition', 'attachment; filename="' . $qr['filename'] . '"');
```

---

### 4. Get System Logo Path

```php
/**
 * Get system logo path from general settings
 *
 * @return string|null Absolute path to logo or null
 */
$logoPath = $qrCodeService->getSystemLogoPath();
```

**Returns:** Absolute file path or `null`

**Use Case:** Validation, debugging, custom logo handling

**Example:**

```php
if ($logo = $qrCodeService->getSystemLogoPath()) {
    // Logo exists and is accessible
}
```

---

### 5. Generate Employee ID Card QR

```php
/**
 * Generate specialized QR for employee ID cards
 *
 * @param object $employee Employee model
 * @param array $options Optional: ['size' => 300, 'margin' => 10, 'logoPath' => null]
 * @return string Base64 encoded QR
 * @throws Exception
 */
$qrCodeService->generateEmployeeQR($employee, $options);
```

**Returns:** Base64 encoded QR code

**Included Data:**

- Employee ID (system_id)
- Full Name
- Department
- Designation
- Mobile Number
- Validity Date (1 year from now)

**Use Case:** Employee ID card generation, badge systems

**Example:**

```php
$employee = Employee::find(1);
$qrCode = $qrCodeService->generateEmployeeQR($employee);

// With custom options
$qrCode = $qrCodeService->generateEmployeeQR($employee, [
    'size' => 400,
    'margin' => 15,
    'logoPath' => null // Use system logo
]);
```

---

### 6. Generate Attendance QR

```php
/**
 * Generate specialized QR for attendance verification
 *
 * @param object $attendance Attendance model
 * @param array $options Optional configuration
 * @return string Base64 encoded QR
 * @throws Exception
 */
$qrCodeService->generateAttendanceQR($attendance, $options);
```

**Returns:** Base64 encoded QR code

**Included Data:**

- Attendance ID
- Employee Name
- Date
- Check-in Time
- Status

**Use Case:** Attendance verification, access logs

**Example:**

```php
$attendance = Attendance::find(123);
$qrCode = $qrCodeService->generateAttendanceQR($attendance);
```

---

### 7. Generate Verification URL QR

```php
/**
 * Generate QR with verification URL
 *
 * @param string $url Verification URL
 * @param array $options Optional configuration
 * @return string Base64 encoded QR
 * @throws Exception
 */
$qrCodeService->generateVerificationQR($url, $options);
```

**Returns:** Base64 encoded QR code

**Use Case:** Document verification, authentication links, secure access

**Example:**

```php
$verificationUrl = route('verification.check', ['token' => $token]);
$qrCode = $qrCodeService->generateVerificationQR($verificationUrl);
```

---

### 8. Cleanup Temporary Files

```php
/**
 * Clean up temporary logo files older than 1 hour
 *
 * @return int Number of deleted files
 */
$deletedCount = $qrCodeService->cleanupTempFiles();
```

**Returns:** Number of deleted files

**Use Case:** Scheduled maintenance, periodic cleanup

**Example (in Scheduler):**

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        app(\App\Services\QrCodeService::class)->cleanupTempFiles();
    })->hourly();
}
```

---

## Implementation Examples

### Example 1: Embedding QR in ID Card Design

**File:** `resources/views/settings/id_design/designs/design_1.blade.php`

```blade
@php
    $qrCodeService = app(\App\Services\QrCodeService::class);

    // Prepare QR data
    $qrText = 'Employee: ' . $employee->full_name . "\n";
    $qrText .= 'ID: ' . $employee->system_id . "\n";
    $qrText .= 'Department: ' . ($currentDepartment?->department_name ?? 'N/A');

    // Generate QR with system logo
    $qrCodeBase64 = $qrCodeService->generateQRBase64($qrText, null, 300, 5);
@endphp

<!-- In your HTML -->
<div class="qr-section">
    <img src="{{ $qrCodeBase64 }}" alt="Employee QR Code" style="width: 100px; height: 100px;">
</div>
```

---

### Example 2: QR Download Route

**File:** `routes/web.php`

```php
Route::get('/qr/download/{type}/{id}', function ($type, $id) {
    $qrService = app(\App\Services\QrCodeService::class);

    if ($type === 'employee') {
        $employee = Employee::findOrFail($id);
        $qrData = "Employee: " . $employee->full_name . "\nID: " . $employee->system_id;
        $qr = $qrService->downloadQR($qrData, null, "employee_{$id}_qr");
    } elseif ($type === 'attendance') {
        $attendance = Attendance::findOrFail($id);
        $qr = $qrService->downloadQR(
            "Attendance: " . $attendance->id,
            null,
            "attendance_{$id}_qr"
        );
    }

    return response($qr['data'], 200)
        ->header('Content-Type', 'image/png')
        ->header('Content-Disposition', 'attachment; filename="' . $qr['filename'] . '"');
})->name('qr.download');
```

---

### Example 3: Controller Implementation

**File:** `app/Http/Controllers/EmployeeIdCardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\QrCodeService;

class EmployeeIdCardController extends Controller
{
    private $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function generateIdCard(Employee $employee)
    {
        // Generate QR code using specialized method
        $qrCode = $this->qrCodeService->generateEmployeeQR($employee, [
            'size' => 350,
            'margin' => 10
        ]);

        return view('employee.id_card', [
            'employee' => $employee,
            'qrCode' => $qrCode
        ]);
    }

    public function downloadQR(Employee $employee)
    {
        $qrData = "Employee: " . $employee->full_name .
                  "\nID: " . $employee->system_id;

        $qr = $this->qrCodeService->downloadQR(
            $qrData,
            null,
            "employee_{$employee->id}_qr"
        );

        return response($qr['data'], 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $qr['filename'] . '"');
    }
}
```

---

### Example 4: PDF Generation with QR

**File:** Using DomPDF or similar

```php
use Barryvdh\DomPDF\Facade as PDF;
use App\Services\QrCodeService;

$qrService = app(QrCodeService::class);
$qrBase64 = $qrService->generateQRBase64('Employee Data', null, 400, 10);

$pdf = PDF::loadView('employee.id_card_pdf', [
    'employee' => $employee,
    'qrCode' => $qrBase64
]);

return $pdf->download('id_card.pdf');
```

**In Blade:**

```blade
<img src="{{ $qrCode }}" alt="QR Code" style="width: 150px; height: 150px;">
```

---

## Error Handling

All methods throw `Exception` on failure. Always wrap in try-catch:

```php
try {
    $qrCode = $qrCodeService->generateQRBase64('Employee Data');
} catch (Exception $e) {
    Log::error('QR generation failed: ' . $e->getMessage());
    // Fallback to placeholder or empty QR
    $qrCode = null;
}
```

**Common Issues:**

| Issue                     | Cause                       | Solution                         |
| ------------------------- | --------------------------- | -------------------------------- |
| Logo not appearing        | Logo file not found         | Check General Settings logo path |
| Storage permission denied | temp directory not writable | Run `chmod -R 775 storage/`      |
| QR generation timeout     | Text too large              | Reduce text length or data       |
| Out of memory             | Large image processing      | Reduce size parameter            |

---

## Logging

All operations are logged with detailed information:

**Log Location:** `storage/logs/laravel.log`

**Logged Events:**

- QR code generation success/failure
- Missing or inaccessible logos
- Logo processing issues
- File cleanup operations
- Download preparation

**View Logs:**

```bash
tail -f storage/logs/laravel.log
```

---

## Performance Optimization

### 1. Cache Generated QR Codes

For frequently accessed QR codes, cache the base64 output:

```php
use Illuminate\Support\Facades\Cache;

$cacheKey = "qr_employee_{$employee->id}";

$qrCode = Cache::remember($cacheKey, 86400, function () use ($employee) {
    return $this->qrCodeService->generateEmployeeQR($employee);
});
```

### 2. Schedule Cleanup

Add to Scheduler in `app/Console/Kernel.php`:

```php
$schedule->call(function () {
    app(\App\Services\QrCodeService::class)->cleanupTempFiles();
})->hourly();
```

### 3. Adjust Size Based on Display

- **Small (web badges):** 200-300px
- **ID Cards:** 300-400px
- **Posters/Signs:** 500-800px
- **Labels:** 100-200px

---

## Supported Image Formats

Logo formats supported for embedding:

- PNG (Recommended)
- JPEG / JPG
- GIF

**Best Practices:**

- Use PNG with transparency for best results
- Keep logo under 100KB
- Use square aspect ratio (1:1) for optimal display
- Recommended minimum: 200x200 pixels

---

## API Response Examples

### Base64 QR Code Response

```
data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALQAAAC0CAIAAACZxMEBAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAC25JREFUeNrs3cluw...
```

### Download Response

```php
[
    'data' => <binary PNG data>,
    'filename' => 'qrcode_1706370458.png'
]
```

---

## Troubleshooting

### QR Code Not Displaying

1. Check if Base64 URL is correct:

    ```blade
    <!-- Should start with data:image/png;base64, -->
    <img src="{{ $qrCode }}" />
    ```

2. Verify QR generation didn't throw exception:
    ```php
    try {
        $qr = $qrCodeService->generateQRBase64('data');
    } catch (Exception $e) {
        dd($e->getMessage());
    }
    ```

### Logo Not Embedding

1. Check logo exists in General Settings
2. Verify file permissions:

    ```bash
    ls -la storage/app/public/
    ```

3. Check logs:
    ```bash
    tail -f storage/logs/laravel.log | grep -i logo
    ```

### Storage Permission Issues

```bash
# Fix permissions
php artisan storage:link
chmod -R 775 storage/app
chmod -R 775 storage/logs
```

---

## Version History

| Version | Date       | Changes                                    |
| ------- | ---------- | ------------------------------------------ |
| 1.0.0   | 2026-01-28 | Initial release with core QR functionality |

---

## Support & Contribution

For issues, questions, or improvements:

1. Check logs: `storage/logs/laravel.log`
2. Verify configuration in General Settings
3. Review this documentation
4. Test in development environment first

---

## License

This QR Code Module is part of the HRMS project.

---

**Last Updated:** January 28, 2026
**Maintained By:** HRMS Development Team
