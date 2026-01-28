# QR Code Debugging Guide

## Problem

QR codes are not showing in the PDF on the new PC, even though everything else works.

## Changes Made

I've added comprehensive logging throughout the QR code generation pipeline to help diagnose the issue. The logging covers:

1. **QR Code Service** (`app/Services/QrCodeService.php`)
    - Logo path detection and validation
    - Image processing with GD library
    - Base64 encoding
    - Temporary file creation

2. **ID Card Service** (`app/Services/IDCardService.php`)
    - HTML rendering
    - PDF generation with Browsershot
    - Template processing

3. **ID Card Template** (`storage/app/public/upload/id_card_designs/designs/*.php`)
    - QR code generation in the template
    - Error handling

## How to Diagnose the Issue

### Step 1: Run the Diagnostic Script

```powershell
cd c:\laragon\www\hrms
php diagnose_qr.php
```

This will check:

- PHP GD extension
- Endroid QR Code package
- Storage directory permissions
- Logo file existence
- QR code generation (with and without logo)
- Node.js and Puppeteer installation

### Step 2: Generate an ID Card

Trigger the ID card generation through your application interface. This will create detailed logs.

### Step 3: Check the Logs

```powershell
# View the most recent log entries
Get-Content storage\logs\laravel.log -Tail 100

# Search for QR-related logs
Select-String -Path storage\logs\laravel.log -Pattern "\[QR\]" | Select-Object -Last 20

# Search for PDF-related logs
Select-String -Path storage\logs\laravel.log -Pattern "\[PDF\]" | Select-Object -Last 20

# Search for template-related logs
Select-String -Path storage\logs\laravel.log -Pattern "\[TEMPLATE\]" | Select-Object -Last 20
```

### Step 4: Clear old logs (optional)

```powershell
# Backup current log
Copy-Item storage\logs\laravel.log storage\logs\laravel.log.backup

# Clear log file
Clear-Content storage\logs\laravel.log
```

## Common Issues and Solutions

### Issue 1: Logo File Not Found

**Symptoms:** Logs show "Logo file does not exist"

**Solution:**

```powershell
# Check if logo files exist
Get-ChildItem storage\app\public\upload\logo
Get-ChildItem storage\app\public\upload\company_logo

# If missing, copy from original PC or upload via admin panel
```

### Issue 2: GD Extension Not Loaded

**Symptoms:** Diagnostic shows "GD extension is NOT loaded"

**Solution:**

1. Open `php.ini` (usually in `c:\laragon\bin\php\php-8.x.x\php.ini`)
2. Find `;extension=gd` and remove the semicolon: `extension=gd`
3. Restart Apache/PHP

### Issue 3: Storage Directory Not Writable

**Symptoms:** Logs show permission errors

**Solution:**

```powershell
# Grant full permissions to storage directory
icacls "c:\laragon\www\hrms\storage" /grant Users:F /T
```

### Issue 4: Puppeteer Not Installed

**Symptoms:** PDF generation fails

**Solution:**

```powershell
npm install puppeteer
```

### Issue 5: Image Processing Fails

**Symptoms:** Logs show errors in `processLogoWithOpacity`

**Solution:**

- Logo file may be corrupted
- Try regenerating QR without logo (it will skip logo automatically)
- Check that logo is a valid PNG/JPG file

### Issue 6: Base64 Encoding Issues

**Symptoms:** QR generated but not visible in PDF

**Solution:**

- Check that base64 string starts with `data:image/png;base64,`
- Verify HTML contains `<img src="data:image/png;base64,...">` tag
- Look for Browsershot rendering errors in logs

## Log Markers to Look For

- `[QR]` - QR code service operations
- `[PDF]` - PDF generation operations
- `[TEMPLATE]` - Template rendering operations

### Successful QR Generation Sequence:

```
[QR] Starting QR code generation
[QR] Using system logo path
[QR] Logo file exists, attempting to process
[QR] Logo opacity processing result
[QR] Logo object created successfully
[QR] Writing QR code
[QR] QR code generated successfully
[QR] Base64 encoding successful
```

### Successful PDF Generation Sequence:

```
[PDF] Starting PDF generation
[PDF] Using design
[PDF] Starting ID card HTML rendering
[PDF] Design template file found
[PDF] Rendering blade template
[PDF] Blade template rendered successfully (should show contains_qr: true)
[PDF] HTML rendered
[PDF] Starting Browsershot PDF generation
[PDF] PDF generated successfully
```

## Testing QR Code Generation Directly

Create a test route to verify QR generation:

```php
// Add to routes/web.php
Route::get('/test-qr', function() {
    $qrService = app(\App\Services\QrCodeService::class);

    try {
        // Test without logo
        $qr1 = $qrService->generateQRBase64('Test QR Code', '', 300, 10);
        echo "<h2>QR Without Logo:</h2>";
        echo "<img src='$qr1' alt='QR Code'>";
        echo "<hr>";

        // Test with logo
        $qr2 = $qrService->generateQRBase64('Test QR Code', null, 300, 10);
        echo "<h2>QR With Logo:</h2>";
        echo "<img src='$qr2' alt='QR Code'>";

    } catch (Exception $e) {
        echo "<h2>Error:</h2>";
        echo "<pre>" . $e->getMessage() . "</pre>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});
```

Visit `http://localhost/test-qr` to see if QR codes generate.

## Quick Fix: Disable Logo in QR Code

If logo processing is causing issues, you can temporarily disable it:

Edit the template file and change:

```php
// FROM:
$qrCodeBase64 = $qrCodeService->generateQRBase64($qrText, $companyLogoPath, 300, 5);

// TO:
$qrCodeBase64 = $qrCodeService->generateQRBase64($qrText, '', 300, 5);
```

## Need More Help?

1. Run the diagnostic script: `php diagnose_qr.php`
2. Generate an ID card
3. Share the log output from `storage/logs/laravel.log` (last 100 lines)
4. Share the diagnostic script output

## Files Modified

1. `app/Services/QrCodeService.php` - Added comprehensive logging
2. `app/Services/IDCardService.php` - Added PDF generation logging
3. `storage/app/public/upload/id_card_designs/designs/1769533024Hu1xKheiHK.php` - Added template logging
4. `diagnose_qr.php` - NEW diagnostic script

All logging uses the `[QR]`, `[PDF]`, and `[TEMPLATE]` prefixes for easy filtering.
