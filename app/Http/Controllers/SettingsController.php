<?php

namespace App\Http\Controllers;

use App\HelperClass;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function generalSettingIndex(Request $request){
        $title = 'General Settings';
        $section = 'General Settings';
        $generalSetting = GeneralSetting::first();
        return view('settings.general_settings', compact('title', 'section', 'generalSetting'));
    }
    public function generalSettingSave(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'logo_light' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Please Enter Name',
            'name.max' => 'Name Must Be Less Than 255 Characters',
            'name.string' => 'Name Must Be String',
            'logo_light.image' => 'Please Upload Image File',
            'logo_light.mimes' => 'Please Upload Image File',
            'logo_light.max' => 'Logo Must Be Less Than 2 MB',
            'logo_dark.image' => 'Please Upload Image File',
            'logo_dark.mimes' => 'Please Upload Image File',
            'logo_dark.max' => 'Logo Must Be Less Than 2 MB',
            'favicon.image' => 'Please Upload Image File',
            'favicon.mimes' => 'Please Upload Image File',
            'favicon.max' => 'Favicon Must Be Less Than 2 MB',
        ]);

        if ($request->id != null){
            $generalSetting = GeneralSetting::find($request->id);
            if ($request->hasFile('logo_light')){
                if($generalSetting->logo_light != null){
                    HelperClass::file_delete($generalSetting->logo_light);
                }
                $logo_path = HelperClass::file_upload($request->file('logo_light'), 'logo');
                $validate['logo_light'] = $logo_path;
            }
            if ($request->hasFile('logo_dark')){
                if($generalSetting->logo_dark != null){
                    HelperClass::file_delete($generalSetting->logo_dark);
                }
                $logo_path = HelperClass::file_upload($request->file('logo_dark'), 'logo');
                $validate['logo_dark'] = $logo_path;
            }

            if ($request->hasFile('favicon')){
                if($generalSetting->favicon != null){
                    HelperClass::file_delete($generalSetting->favicon);
                }
                $favicon_path = HelperClass::file_upload($request->file('favicon'), 'logo');
                $validate['favicon'] = $favicon_path;
            }

            $generalSetting->update($validate);
        }else{
            if ($request->hasFile('logo_light')){
                $logo_path = HelperClass::file_upload($request->file('logo_light'), 'logo');
                $validate['logo_light'] = $logo_path;
            }
            if ($request->hasFile('logo_dark')){
                $logo_path = HelperClass::file_upload($request->file('logo_dark'), 'logo');
                $validate['logo_dark'] = $logo_path;
            }
            if ($request->hasFile('favicon')){
                $favicon_path = HelperClass::file_upload($request->file('favicon'), 'logo');
                $validate['favicon'] = $favicon_path;
            }
            GeneralSetting::create($validate);
        }

        return redirect()->back()->with([
            'message' => 'General Setting Saved Successfully',
            'alert-type' => 'success'
        ]);

    }
}
