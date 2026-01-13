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
    public static function getHoursByMinutes($minutes){
        $isNegative = $minutes < 0;
        $minutes = abs($minutes);
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;
        $formatted = $hours . ' hr ' . $mins . ' min';
        return $isNegative ? '-' . $formatted : $formatted;
    }



}
