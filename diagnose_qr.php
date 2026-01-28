<?php
/**
 * QR Code Diagnostic Script
 *
 * This script helps diagnose QR code generation issues in the HRMS system
 * Run this from command line: php diagnose_qr.php
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=================================================\n";
echo "HRMS QR Code Diagnostic Tool\n";
echo "=================================================\n\n";

// 1. Check PHP GD Extension
echo "1. Checking PHP GD Extension...\n";
if (extension_loaded('gd')) {
    echo "   ✓ GD extension is loaded\n";
    $gdInfo = gd_info();
    echo "   - GD Version: " . ($gdInfo['GD Version'] ?? 'Unknown') . "\n";
    echo "   - PNG Support: " . ($gdInfo['PNG Support'] ? 'Yes' : 'No') . "\n";
    echo "   - JPEG Support: " . ($gdInfo['JPEG Support'] ?? $gdInfo['JPG Support'] ?? false ? 'Yes' : 'No') . "\n";
} else {
    echo "   ✗ GD extension is NOT loaded (CRITICAL)\n";
}
echo "\n";

// 2. Check Endroid QR Code Package
echo "2. Checking Endroid QR Code Package...\n";
if (class_exists('Endroid\QrCode\QrCode')) {
    echo "   ✓ Endroid QR Code package is installed\n";
} else {
    echo "   ✗ Endroid QR Code package is NOT installed (CRITICAL)\n";
}
echo "\n";

// 3. Check Storage Directories
echo "3. Checking Storage Directories...\n";
$storagePaths = [
    'storage/app/temp' => storage_path('app/temp'),
    'storage/app/public' => storage_path('app/public'),
    'storage/app/public/upload/logo' => storage_path('app/public/upload/logo'),
    'storage/logs' => storage_path('logs'),
];

foreach ($storagePaths as $name => $path) {
    if (file_exists($path)) {
        $writable = is_writable($path);
        echo "   " . ($writable ? '✓' : '✗') . " $name: " . ($writable ? 'writable' : 'NOT writable') . "\n";
        echo "      Path: $path\n";
    } else {
        echo "   ✗ $name: does NOT exist\n";
        echo "      Path: $path\n";
        // Try to create it
        if (@mkdir($path, 0755, true)) {
            echo "      ✓ Successfully created directory\n";
        } else {
            echo "      ✗ Failed to create directory\n";
        }
    }
}
echo "\n";

// 4. Check General Settings and Logo
echo "4. Checking General Settings and Logo...\n";
try {
    $generalSettings = \App\Models\GeneralSetting::first();
    if ($generalSettings) {
        echo "   ✓ General Settings found\n";
        echo "   - Company Name: " . ($generalSettings->company_name ?? 'Not set') . "\n";
        echo "   - Logo Light: " . ($generalSettings->logo_light ?? 'Not set') . "\n";
        echo "   - Logo Dark: " . ($generalSettings->logo_dark ?? 'Not set') . "\n";

        $logoField = $generalSettings->logo_light ?? $generalSettings->logo_dark;
        if ($logoField) {
            $logoPath = storage_path('app/public/' . $logoField);
            if (file_exists($logoPath)) {
                echo "   ✓ Logo file exists: $logoPath\n";
                echo "   - File size: " . filesize($logoPath) . " bytes\n";

                $imageInfo = @getimagesize($logoPath);
                if ($imageInfo) {
                    echo "   - Image size: {$imageInfo[0]}x{$imageInfo[1]}\n";
                    echo "   - MIME type: {$imageInfo['mime']}\n";
                } else {
                    echo "   ✗ Cannot read image info (file may be corrupted)\n";
                }
            } else {
                echo "   ✗ Logo file does NOT exist: $logoPath\n";
            }
        } else {
            echo "   ⚠ No logo configured in General Settings\n";
        }
    } else {
        echo "   ✗ No General Settings found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error checking General Settings: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Test QR Code Generation
echo "5. Testing QR Code Generation...\n";
try {
    $qrCodeService = app(\App\Services\QrCodeService::class);

    // Test 1: Simple QR without logo
    echo "   Test 1: Simple QR without logo...\n";
    $testData = "Test QR Code - " . date('Y-m-d H:i:s');
    $qrBase64 = $qrCodeService->generateQRBase64($testData, '', 300, 10);
    if (!empty($qrBase64) && str_starts_with($qrBase64, 'data:image/png;base64,')) {
        echo "   ✓ Simple QR generation successful\n";
        echo "   - Base64 length: " . strlen($qrBase64) . " characters\n";
    } else {
        echo "   ✗ Simple QR generation failed\n";
    }

    // Test 2: QR with system logo
    echo "\n   Test 2: QR with system logo...\n";
    try {
        $qrBase64WithLogo = $qrCodeService->generateQRBase64($testData, null, 300, 10);
        if (!empty($qrBase64WithLogo) && str_starts_with($qrBase64WithLogo, 'data:image/png;base64,')) {
            echo "   ✓ QR with logo generation successful\n";
            echo "   - Base64 length: " . strlen($qrBase64WithLogo) . " characters\n";
        } else {
            echo "   ✗ QR with logo generation failed\n";
        }
    } catch (Exception $e) {
        echo "   ✗ QR with logo generation failed: " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "   ✗ QR Code Service error: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Check Browsershot/Puppeteer
echo "6. Checking Browsershot/Puppeteer...\n";
$nodeBinary = config('browsershot.node_binary', 'node');
$npmBinary = config('browsershot.npm_binary', 'npm');
$nodeModulesPath = config('browsershot.node_modules_path', base_path('node_modules'));

echo "   - Node Binary: $nodeBinary\n";
exec("$nodeBinary --version 2>&1", $nodeOutput, $nodeReturnCode);
if ($nodeReturnCode === 0) {
    echo "   ✓ Node.js is available: " . implode(' ', $nodeOutput) . "\n";
} else {
    echo "   ✗ Node.js is NOT available or path is incorrect\n";
}

echo "   - Node Modules Path: $nodeModulesPath\n";
$puppeteerPath = $nodeModulesPath . '/puppeteer';
if (file_exists($puppeteerPath)) {
    echo "   ✓ Puppeteer is installed\n";
} else {
    echo "   ✗ Puppeteer is NOT installed (CRITICAL for PDF generation)\n";
    echo "      Run: npm install puppeteer\n";
}
echo "\n";

// 7. Check Recent Logs
echo "7. Checking Recent Logs...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    echo "   ✓ Log file exists: $logFile\n";
    $fileSize = filesize($logFile);
    echo "   - File size: " . number_format($fileSize) . " bytes (" . round($fileSize / 1024 / 1024, 2) . " MB)\n";
    echo "   - Last modified: " . date('Y-m-d H:i:s', filemtime($logFile)) . "\n";

    if ($fileSize > 10 * 1024 * 1024) { // If log is larger than 10MB
        echo "   ⚠ Log file is very large. Reading last 100 lines only...\n";
        // Read only the last part of the file
        $handle = fopen($logFile, 'r');
        fseek($handle, -min($fileSize, 50000), SEEK_END); // Read last ~50KB
        $lastPart = fread($handle, 50000);
        fclose($handle);
        $lines = explode("\n", $lastPart);
    } else {
        $logContent = file_get_contents($logFile);
        $lines = explode("\n", $logContent);
    }

    echo "\n   Recent QR-related log entries:\n";
    $recentQrLogs = [];
    foreach (array_reverse($lines) as $line) {
        if (stripos($line, '[QR]') !== false || stripos($line, 'QR Code') !== false) {
            $recentQrLogs[] = $line;
            if (count($recentQrLogs) >= 10) break;
        }
    }
    if (!empty($recentQrLogs)) {
        foreach (array_reverse($recentQrLogs) as $log) {
            echo "   " . substr($log, 0, 150) . "\n";
        }
    } else {
        echo "   (No recent QR-related logs found - Generate an ID card to create logs)\n";
    }
} else {
    echo "   ✗ Log file does not exist\n";
}
echo "\n";

echo "=================================================\n";
echo "Diagnostic Complete!\n";
echo "=================================================\n\n";

echo "RECOMMENDATIONS:\n";
echo "1. Check storage/logs/laravel.log for detailed error messages\n";
echo "2. Generate an ID card to trigger logging\n";
echo "3. Look for [QR], [PDF], and [TEMPLATE] tags in logs\n";
echo "4. Ensure logo files exist in storage/app/public/upload/logo/\n";
echo "5. Verify GD extension and Puppeteer are installed\n";
echo "\n";
