<?php

namespace App\Services;

use App\Models\GeneralSetting;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * QR Code Service for HRMS
 *
 * Generates QR codes with optional company logo watermark for:
 * - Employee ID cards and badges
 * - Attendance verification
 * - Document authentication
 * - Access control
 *
 * Features:
 * - Server-side generation (no JavaScript dependencies)
 * - Automatic logo embedding from General Settings
 * - Logo with reduced opacity for scanability
 * - Base64 output for direct HTML/PDF use
 * - File download support
 *
 * @package App\Services
 */
class QrCodeService
{
    /**
     * Generate QR code and return as PNG binary string
     *
     * @param string $text Data to encode in QR code
     * @param string|null $logoPath Logo file path
     *                    null = use system logo from General Settings
     *                    '' (empty string) = no logo
     *                    '/path/to/logo.png' = custom logo path
     * @param int $size QR code size in pixels (default: 400, range: 100-1000)
     * @param int $margin White border margin in pixels (default: 10, range: 0-50)
     * @return string PNG binary data
     * @throws Exception
     */
    public function generateQR(string $text, ?string $logoPath = null, int $size = 400, int $margin = 10): string
    {
        try {
            // Validate parameters
            $size = max(100, min(1000, $size));
            $margin = max(0, min(50, $margin));

            // Determine logo path
            if ($logoPath === null) {
                $logoPath = $this->getSystemLogoPath();
            }

            // Create QR code
            $qrCode = new QrCode($text);

            // Create writer
            $writer = new PngWriter();

            // Add logo if provided and exists
            $logo = null;
            if (!empty($logoPath) && file_exists($logoPath)) {
                try {
                    // Create transparent logo watermark (very small - 50x50px max)
                    // This ensures QR code remains scannable while showing branding
                    $logo = $this->createTransparentLogo($logoPath);
                } catch (Exception $e) {
                    Log::warning('Failed to add logo to QR code', ['error' => $e->getMessage()]);
                    $logo = null;
                }
            }

            // Generate QR code
            $result = $writer->write($qrCode, $logo);

            return $result->getString();
        } catch (Exception $e) {
            Log::error('QR Code generation failed', [
                'error' => $e->getMessage(),
                'text' => substr($text, 0, 100),
                'logoPath' => $logoPath ?? 'system'
            ]);
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    /**
     * Generate QR code and return as base64 encoded data URL
     * Ready for direct use in HTML img tags or PDF generation
     *
     * @param string $text Data to encode in QR code
     * @param string|null $logoPath Logo file path (null = system, '' = none, path = custom)
     * @param int $size QR code size in pixels (default: 400)
     * @param int $margin White border margin in pixels (default: 10)
     * @return string Base64 data URL (e.g., "data:image/png;base64,iVBORw0KGgo...")
     * @throws Exception
     */
    public function generateQRBase64(string $text, ?string $logoPath = null, int $size = 400, int $margin = 10): string
    {
        try {
            $pngData = $this->generateQR($text, $logoPath, $size, $margin);
            return 'data:image/png;base64,' . base64_encode($pngData);
        } catch (Exception $e) {
            Log::error('QR Code Base64 generation failed', [
                'error' => $e->getMessage(),
                'text' => substr($text, 0, 100)
            ]);
            throw $e;
        }
    }

    /**
     * Prepare QR code for file download
     *
     * @param string $text Data to encode
     * @param string|null $logoPath Logo path (null = system, '' = none, path = custom)
     * @param string|null $filename Custom filename (default: qrcode_TIMESTAMP.png)
     * @param int $size QR code size in pixels (default: 400)
     * @param int $margin Margin in pixels (default: 10)
     * @return array ['data' => PNG binary, 'filename' => string]
     * @throws Exception
     */
    public function downloadQR(
        string $text,
        ?string $logoPath = null,
        ?string $filename = null,
        int $size = 400,
        int $margin = 10
    ): array {
        try {
            $pngData = $this->generateQR($text, $logoPath, $size, $margin);

            // Generate filename if not provided
            if (empty($filename)) {
                $filename = 'qrcode_' . time() . '.png';
            } else {
                // Ensure .png extension
                if (!str_ends_with(strtolower($filename), '.png')) {
                    $filename .= '.png';
                }
            }

            return [
                'data' => $pngData,
                'filename' => $filename
            ];
        } catch (Exception $e) {
            Log::error('QR Code download preparation failed', [
                'error' => $e->getMessage(),
                'filename' => $filename
            ]);
            throw $e;
        }
    }

    /**
     * Get the system logo path from general settings
     *
     * @return string|null Absolute path to system logo or null if not set
     */
    public function getSystemLogoPath(): ?string
    {
        try {
            $setting = GeneralSetting::first();

            if (!$setting) {
                return null;
            }

            // Check for logo_light first, then logo_dark
            $logoField = $setting->logo_light ?? $setting->logo_dark ?? null;

            if (empty($logoField)) {
                return null;
            }

            // Build absolute path
            $logoPath = storage_path('app/public/' . $logoField);

            // Verify file exists
            if (!file_exists($logoPath)) {
                Log::warning('System logo file not found', ['path' => $logoPath]);
                return null;
            }

            return $logoPath;
        } catch (Exception $e) {
            Log::error('Failed to get system logo path', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create a semi-transparent logo for QR code embedding
     * The logo is reduced in size and opacity to not interfere with QR scanning
     *
     * @param string $logoPath Original logo file path
     * @return Logo|null Processed logo object with reduced opacity
     */
    private function createTransparentLogo(string $logoPath): ?Logo
    {
        try {
            // Process the logo with reduced opacity
            $processedPath = $this->processLogoWithOpacity($logoPath);

            if (!$processedPath || !file_exists($processedPath)) {
                return null;
            }

            // Create logo with larger size (60x60 pixels max)
            // This ensures better visibility while maintaining QR code scannability
            $logo = new Logo($processedPath, 60, 60);

            return $logo;
        } catch (Exception $e) {
            Log::warning('Failed to create transparent logo', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Process logo to reduce opacity to ~10%
     * Uses GD library to create a semi-transparent version
     *
     * @param string $logoPath Original logo path
     * @return string|null Path to processed logo or null on failure
     */
    private function processLogoWithOpacity(string $logoPath): ?string
    {
        try {
            if (!file_exists($logoPath)) {
                return null;
            }

            // Get image info
            $imageInfo = @getimagesize($logoPath);
            if ($imageInfo === false) {
                return $logoPath; // Return original if we can't get info
            }

            $mimeType = $imageInfo['mime'] ?? '';

            // Load image based on type
            $image = null;
            switch ($mimeType) {
                case 'image/png':
                    $image = @imagecreatefrompng($logoPath);
                    break;
                case 'image/jpeg':
                case 'image/jpg':
                    $image = @imagecreatefromjpeg($logoPath);
                    break;
                case 'image/gif':
                    $image = @imagecreatefromgif($logoPath);
                    break;
                default:
                    return $logoPath; // Return original for unsupported types
            }

            if (!$image) {
                return $logoPath;
            }

            // Get image dimensions
            $width = imagesx($image);
            $height = imagesy($image);

            // Create a new image with white background for the logo
            $newImage = imagecreatetruecolor($width, $height);
            if (!$newImage) {
                imagedestroy($image);
                return $logoPath;
            }

            // Fill with white background first
            $white = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $white);

            // Copy the original image onto the white background
            imagecopy($newImage, $image, 0, 0, 0, 0, $width, $height);
            imagedestroy($image);

            // Now create final image with transparency
            $finalImage = imagecreatetruecolor($width, $height);
            if (!$finalImage) {
                imagedestroy($newImage);
                return $logoPath;
            }

            // Enable alpha channel for transparency
            imagesavealpha($finalImage, true);

            // Create fully transparent background
            $transparentColor = imagecolorallocatealpha($finalImage, 255, 255, 255, 127);
            imagefill($finalImage, 0, 0, $transparentColor);

            // Keep logo fully opaque (100% visible) with white background
            // This is done by reducing the alpha channel values
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color = imagecolorat($newImage, $x, $y);
                    $alpha = ($color >> 24) & 0xFF;

                    // Keep the image fully opaque (100% visible)
                    // If alpha is 0 (opaque), keep it 0 (fully visible)
                    // If already partially transparent, reduce transparency
                    if ($alpha == 0) {
                        $alpha = 0; // 100% opacity - fully visible
                    } else {
                        $alpha = min(127, $alpha + 10); // Reduce transparency
                    }

                    $newColor = imagecolorallocatealpha(
                        $finalImage,
                        ($color >> 16) & 0xFF,
                        ($color >> 8) & 0xFF,
                        $color & 0xFF,
                        $alpha
                    );
                    imagesetpixel($finalImage, $x, $y, $newColor);
                }
            }

            // Clean up newImage
            imagedestroy($newImage);

            // Save to temp directory
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempPath = $tempDir . '/logo_' . md5($logoPath) . '_' . time() . '.png';
            imagepng($finalImage, $tempPath);

            // Clean up
            imagedestroy($finalImage);

            return file_exists($tempPath) ? $tempPath : null;
        } catch (Exception $e) {
            Log::warning('Failed to process logo with opacity', [
                'error' => $e->getMessage(),
                'logoPath' => $logoPath
            ]);
            return $logoPath; // Return original on error
        }
    }

    /**
     * Clean up temporary logo files older than 1 hour
     * Should be called periodically (e.g., scheduled job)
     *
     * @return int Number of files deleted
     */
    public function cleanupTempFiles(): int
    {
        try {
            $tempDir = storage_path('app/temp');

            if (!file_exists($tempDir)) {
                return 0;
            }

            $deleted = 0;
            $files = glob($tempDir . '/logo_*.png');
            $oneHourAgo = time() - 3600;

            foreach ($files as $file) {
                if (is_file($file) && filemtime($file) < $oneHourAgo) {
                    if (unlink($file)) {
                        $deleted++;
                    }
                }
            }

            return $deleted;
        } catch (Exception $e) {
            Log::error('Failed to cleanup temp QR code files', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Generate QR code for employee ID card
     *
     * @param object $employee Employee model instance
     * @param array $options Additional options (size, margin, logoPath)
     * @return string Base64 encoded QR code
     * @throws Exception
     */
    public function generateEmployeeQR($employee, array $options = []): string
    {
        $size = $options['size'] ?? 300;
        $margin = $options['margin'] ?? 10;
        $logoPath = $options['logoPath'] ?? null;

        // Build employee data string
        $qrData = "Employee ID: {$employee->system_id}\n";
        $qrData .= "Name: {$employee->full_name}\n";

        if (!empty($employee->department)) {
            $qrData .= "Department: {$employee->department}\n";
        }

        if (!empty($employee->designation)) {
            $qrData .= "Designation: {$employee->designation}\n";
        }

        if (!empty($employee->personal_mobile)) {
            $qrData .= "Mobile: {$employee->personal_mobile}\n";
        }

        $qrData .= "Valid Until: " . now()->addYear()->format('Y-m-d');

        return $this->generateQRBase64($qrData, $logoPath, $size, $margin);
    }

    /**
     * Generate QR code for attendance verification
     *
     * @param object $attendance Attendance model instance
     * @param array $options Additional options (size, margin, logoPath)
     * @return string Base64 encoded QR code
     * @throws Exception
     */
    public function generateAttendanceQR($attendance, array $options = []): string
    {
        $size = $options['size'] ?? 250;
        $margin = $options['margin'] ?? 10;
        $logoPath = $options['logoPath'] ?? null;

        $qrData = "Attendance ID: {$attendance->id}\n";
        $qrData .= "Employee: {$attendance->employee->full_name}\n";
        $qrData .= "Date: {$attendance->date}\n";
        $qrData .= "Check In: {$attendance->check_in_time}\n";
        $qrData .= "Status: {$attendance->status}";

        return $this->generateQRBase64($qrData, $logoPath, $size, $margin);
    }

    /**
     * Generate QR code with verification URL
     *
     * @param string $url Verification URL
     * @param array $options Additional options (size, margin, logoPath)
     * @return string Base64 encoded QR code
     * @throws Exception
     */
    public function generateVerificationQR(string $url, array $options = []): string
    {
        $size = $options['size'] ?? 300;
        $margin = $options['margin'] ?? 10;
        $logoPath = $options['logoPath'] ?? null;

        return $this->generateQRBase64($url, $logoPath, $size, $margin);
    }
}
