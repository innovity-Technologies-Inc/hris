<?php

namespace App;


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



}
