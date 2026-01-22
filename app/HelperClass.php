<?php

namespace App;


use App\Models\GeneralSetting;
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
        $file_name = time() . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file_path = $file->storeAs('upload/' . $folder_name, $file_name, 'public');
        return $file_path;
    }

    public static function file_delete($file_path)
    {
        Storage::disk('public')->delete($file_path);
    }

    public static function indexNumberSerialization($data){
        $sl = ($data->currentPage() - 1) * $data->perPage() + 1;
        return $sl;
    }


    public static function getCurrency(){
        $data = GeneralSetting::first()->currency;
        return $data;
    }

    public static function getGeneralSetting(){
        $data = GeneralSetting::first();
        return $data;
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
     * @return string HTML string
     */
    public static function generateAvatar($photoPath, $fullName, $size = 45, $bgColor = '#974063', $extraClass = '')
    {
        // Check if photo exists and file is valid
        if (!empty($photoPath) && file_exists(public_path('storage/' . $photoPath))) {
            return '<img src="' . asset('storage/' . $photoPath) . '"
                    alt="' . htmlspecialchars($fullName ?? 'User') . '"
                    class="rounded-circle ' . $extraClass . '"
                    style="width: ' . $size . 'px; height: ' . $size . 'px; object-fit: cover; display: inline-block;">';
        }

        // Generate avatar with initials
        $initials = self::getInitials($fullName);
        $fontSize = round($size * 0.4); // Font size is 40% of avatar size

        return '<div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold ' . $extraClass . '"
                style="width: ' . $size . 'px; height: ' . $size . 'px; font-size: ' . $fontSize . 'px; background-color: ' . $bgColor . ';">
                ' . $initials . '
            </div>';
    }


}
