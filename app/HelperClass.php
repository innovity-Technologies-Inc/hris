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
            $photoUrl = Storage::disk($disk)->url($photoPath);
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
     */
    public static function get_file_url($file_path)
    {
        if (empty($file_path)) {
            return null;
        }
        $disk = config('filesystems.default');
        return Storage::disk($disk)->url($file_path);
    }
}

