<?php

namespace App;


use App\Models\Setting\GeneralSetting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class HelperClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function file_upload($file, $folder_name)
    {
        $disk = config('filesystems.default');
        $file_name = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file_path = $file->storeAs('upload/' . $folder_name, $file_name, [
            'disk' => $disk,
            'visibility' => 'public'
        ]);
        return $file_path;
    }

    public static function file_delete($file_path)
    {
        $disk = config('filesystems.default');
        Storage::disk($disk)->delete($file_path);
    }

    public static function indexNumberSerialization($data){
        $sl = ($data->currentPage() - 1) * $data->perPage() + 1;
        return $sl;
    }


    public static function getCurrency(){
        $setting = GeneralSetting::first();
        return $setting ? $setting->currency : '৳';
    }

    public static function getGeneralSetting(){
        $data = GeneralSetting::first();
        return $data;
    }

    public static function getTransferSetting(){
        return \App\Models\Setting\TransferSetting::first();
    }
    public static function getHoursByMinutes($minutes){
        $isNegative = $minutes < 0;
        $minutes = abs($minutes);
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        $formatted = $hours . ' hr ' . $mins . ' min';
        return $isNegative ? '-' . $formatted : $formatted;
    }

    /**
     * Get initials from a full name (first letter of first name + first letter of last name)
     * Example: "Ashraf Roy" returns "AR"
     */
    public static function getInitials($fullName)
    {
        if (empty($fullName) || $fullName === 'N/A') {
            return 'NA';
        }

        $fullName = trim($fullName);
        $names = explode(' ', $fullName);

        // Get first letter of first name
        $initials = strtoupper(substr($names[0], 0, 1));

        // Get first letter of last name if exists
        if (count($names) > 1) {
            $initials .= strtoupper(substr($names[count($names) - 1], 0, 1));
        }

        return $initials;
    }

    /**
     * Generate avatar HTML with initials or image
     *
     * @param string|null $photoPath - Path to photo in storage
     * @param string $fullName - Full name for initials
     * @param int $size - Size in pixels (default 45)
     * @param string $bgColor - Background color (default #974063)
     * @param string $extraClass - Additional CSS classes
     * @param int|null $employeeId - Employee ID for profile link (optional)
     * @return string HTML string
     */
    public static function generateAvatar($photoPath, $fullName, $size = 45, $bgColor = '#974063', $extraClass = '', $employeeId = null)
    {
        $avatarHtml = '';
        $disk = config('filesystems.default');

        // Determine if file exists
        $fileExists = false;
        if (!empty($photoPath)) {
            if ($disk === 'local' || $disk === 'public') {
                $fileExists = file_exists(public_path('storage/' . $photoPath)) || file_exists(storage_path('app/public/' . $photoPath));
            } else {
                // For cloud/S3/MinIO, we don't perform slow synchronous head requests on page load
                $fileExists = true;
            }
        }

        if ($fileExists) {
            $photoUrl = self::get_file_url($photoPath);
            $avatarHtml = '<img src="' . $photoUrl . '"
                    alt="' . htmlspecialchars($fullName ?? 'User') . '"
                    class="rounded-circle ' . $extraClass . '"
                    style="width: ' . $size . 'px; height: ' . $size . 'px; object-fit: cover; display: inline-block;">';
        } else {
            // Generate avatar with initials
            $initials = self::getInitials($fullName);
            $fontSize = round($size * 0.4); // Font size is 40% of avatar size

            $avatarHtml = '<div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold ' . $extraClass . '"
                    style="width: ' . $size . 'px; height: ' . $size . 'px; font-size: ' . $fontSize . 'px; background-color: ' . $bgColor . ';">
                    ' . $initials . '
                </div>';
        }

        // Wrap with profile link if employee ID is provided
        if (!empty($employeeId)) {
            $profileUrl = route('employee.profile.general_informations', $employeeId);
            return '<a href="' . $profileUrl . '" class="text-decoration-none" style="cursor: pointer;" title="View Profile">' . $avatarHtml . '</a>';
        }

        return $avatarHtml;
    }

    /**
     * Convert number to English words
     */
    public static function numberToWords($number)
    {
        $number = floor($number);
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'numberToWords only accepts integers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . self::numberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[(int) $hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    /**
     * Check if a profile field is configured as required.
     */
    public static function isProfileFieldRequired($section, $fieldName)
    {
        static $configs = null;
        if ($configs === null) {
            if (\Illuminate\Support\Facades\Schema::hasTable('profile_field_configs')) {
                $configs = \App\Models\Setting\ProfileFieldConfig::all();
            } else {
                $configs = collect();
            }
        }

        $config = $configs->where('section', $section)->where('field_name', $fieldName)->first();
        return $config ? (bool)$config->is_required : false;
    }

    /**
     * Get URL for any file path dynamically from the configured storage disk.
     *
     * - For `local` / `public` disks: returns the standard public storage URL.
     * - For `minio` / `s3` disks: returns a Laravel proxy route URL that streams
     *   the file securely through the application, supporting private bucket access.
     */
    public static function get_file_url($file_path): ?string
    {
        if (empty($file_path)) {
            return null;
        }

        // Already a full URL (e.g. external link stored in DB) — return as-is
        if (str_starts_with($file_path, 'http://') || str_starts_with($file_path, 'https://')) {
            return $file_path;
        }

        // Local public asset paths (e.g. assets/images/favicon.png, images/logo.png)
        // These live in the /public directory and must NEVER go through MinIO proxy.
        // Return them directly using the asset() helper.
        $localAssetPrefixes = ['assets/', 'images/', 'css/', 'js/', 'fonts/', 'storage/app/public/'];
        foreach ($localAssetPrefixes as $prefix) {
            if (str_starts_with($file_path, $prefix)) {
                return asset($file_path);
            }
        }

        $disk = config('filesystems.default', 'public');

        /*
        |--------------------------------------------------------------------------
        | Cloud Proxy Route (Commented Out)
        |--------------------------------------------------------------------------
        | Used for cloud/private disks (minio, s3) to proxy file delivery.
        | PHP signs the URL, and Nginx X-Accel-Redirect fetches the file from MinIO
        | directly, or falls back to route('file.serve') for PHP streaming if Nginx
        | is not available. This prevents public visibility issues for private buckets.
        | Currently disabled to return the direct path/URL instead.
        |
        if (in_array($disk, ['minio', 's3'])) {
            // Encode the path so slashes and special chars survive the URL safely
            $encodedPath = base64_encode($file_path);
            return route('file.accel', ['encodedPath' => $encodedPath]);
        }
        */

        // For local disk, serve via the public symlink disk
        if ($disk === 'local') {
            $disk = 'public';
        }

        return Storage::disk($disk)->url($file_path);
    }

    /**
     * Check if a file exists dynamically from the configured storage disk.
     */
    public static function file_exists($file_path): bool
    {
        if (empty($file_path)) {
            return false;
        }

        $disk = config('filesystems.default', 'public');

        if ($disk === 'local') {
            $disk = 'public';
        }

        try {
            return Storage::disk($disk)->exists($file_path);
        } catch (\Exception $e) {
            \Log::error('Error checking file existence on storage: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Configure a Browsershot instance with standard parameters.
     */
    public static function configureBrowsershot(\Spatie\Browsershot\Browsershot $browsershot): \Spatie\Browsershot\Browsershot
    {
        $nodeBinary = config('browsershot.node_binary', 'node');
        $npmBinary = config('browsershot.npm_binary', 'npm');
        $nodeModulesPath = config('browsershot.node_modules_path', base_path('node_modules'));
        $chromePath = config('browsershot.chrome_path');
        
        $chromeArgs = config('browsershot.chrome_arguments', [
            'disable-gpu',
            'no-sandbox',
            'disable-dev-shm-usage',
        ]);
        
        $cleanedArgs = [];
        foreach ($chromeArgs as $arg) {
            $cleanedArgs[] = ltrim($arg, '-');
        }

        $browsershot
            ->setNodeBinary($nodeBinary)
            ->setNodeModulePath($nodeModulesPath)
            ->addChromiumArguments($cleanedArgs)
            ->timeout(config('browsershot.timeout', 60));

        if ($npmBinary && $npmBinary !== 'npm') {
            $browsershot->setNpmBinary($npmBinary);
        }

        if ($chromePath) {
            $browsershot->setChromePath($chromePath);
        }

        return $browsershot;
    }

    /**
     * Get the base64 data URI of a storage file.
     */
    public static function image_to_base64($file_path): ?string
    {
        if (empty($file_path)) {
            return null;
        }

        $disk = config('filesystems.default', 'public');
        if ($disk === 'local') {
            $disk = 'public';
        }

        try {
            if (!Storage::disk($disk)->exists($file_path)) {
                return null;
            }
            $content = Storage::disk($disk)->get($file_path);
            $mimeType = Storage::disk($disk)->mimeType($file_path) ?? 'image/png';
            return 'data:' . $mimeType . ';base64,' . base64_encode($content);
        } catch (\Exception $e) {
            \Log::error('Error generating base64 for file: ' . $e->getMessage());
            return null;
        }
    }

    public static function get_id_card_image($path, $fallbackType = 'logo'): string
    {
        \Log::info('[IMAGE_HELPER] Resolving path: ' . $path);
        if (empty($path)) {
            return self::get_id_card_fallback_svg($fallbackType);
        }

        // If it is already a URL or base64, return it
        if (strpos($path, 'data:image') === 0 || strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            \Log::info('[IMAGE_HELPER] Path is already URL or Base64');
            return $path;
        }

        $base64 = self::image_to_base64($path);
        if ($base64) {
            \Log::info('[IMAGE_HELPER] Base64 conversion successful. Length: ' . strlen($base64));
            return $base64;
        }

        \Log::info('[IMAGE_HELPER] Base64 conversion failed. Returning fallback SVG.');
        return self::get_id_card_fallback_svg($fallbackType);
    }

    /**
     * Get fallback SVG string for logo or photo.
     */
    private static function get_id_card_fallback_svg($type): string
    {
        if ($type === 'photo') {
            return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 120'%3E%3Crect fill='%23e3f2fd' width='100' height='120'/%3E%3Cpath d='M50 45c8 0 14-6 14-14s-6-14-14-14-14 6-14 14 6 14 14 14zm0 5c-10 0-30 5-30 15v8h60v-8c0-10-20-15-30-15z' fill='%231e88e5' transform='translate(0 10)'/%3E%3C/svg%3E";
        }

        // Default logo fallback
        return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%231e88e5' width='100' height='100' rx='10'/%3E%3Ctext x='50' y='60' font-size='35' fill='white' text-anchor='middle' font-family='Arial' font-weight='bold'%3EGT%3C/text%3E%3C/svg%3E";
    }
}

