<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\HelperClass;
use App\Mail\Dashboard\TestMail;
use App\Models\Setting\GeneralSetting;
use App\Models\Setting\MailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function generalSettingIndex(Request $request){
        $title = 'General Settings';
        $section = 'General Settings';
        $generalSetting = GeneralSetting::first();
        return view('setting.general_settings', compact('title', 'section', 'generalSetting'));
    }
    public function generalSettingSave(Request $request){
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'logo_light' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'currency' => 'required|string|max:255',
            'branch_status' => 'nullable',
            'division_status' => 'nullable',
            'department_status' => 'nullable',
            'section_status' => 'nullable',
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
        try {
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
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'General Setting Saved Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function mailSettingIndex(Request $request){
        $title = 'Mail Settings';
        $section = 'Settings';
        $sub_section = 'Mail Settings';
        $mailSetting = MailSetting::first();
        return view('setting.mail_settings', compact('title', 'section', 'sub_section', 'mailSetting'));
    }

    public function mailSettingSave(Request $request){
        $request->validate([
            'app_name' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'encryption_type' => 'required|string|in:enc-type,ssl,tls',
            'sender_email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
        ], [
            'app_name.required' => 'Please Enter App Name',
            'app_name.string' => 'App Name Must Be String',
            'app_name.max' => 'App Name Must Be Less Than 255 Characters',
            'mail_host.required' => 'Please Enter Mail Host',
            'mail_host.string' => 'Mail Host Must Be String',
            'mail_host.max' => 'Mail Host Must Be Less Than 255 Characters',
            'encryption_type.required' => 'Please Select Encryption Type',
            'encryption_type.in' => 'Encryption Type Must Be enc-type, ssl, or tls',
            'sender_email.required' => 'Please Enter Sender Email',
            'sender_email.email' => 'Please Enter Valid Email Address',
            'sender_email.max' => 'Sender Email Must Be Less Than 255 Characters',
            'password.required' => 'Please Enter Password',
            'password.string' => 'Password Must Be String',
            'password.max' => 'Password Must Be Less Than 255 Characters',
            'port.required' => 'Please Enter Port',
            'port.integer' => 'Port Must Be Integer',
            'port.min' => 'Port Must Be Greater Than 0',
            'port.max' => 'Port Must Be Less Than 65535',
        ]);

        try {
            $mailSetting = MailSetting::first();

            if ($mailSetting) {
                // Update existing record
                $mailSetting->update([
                    'app_name' => $request->app_name,
                    'mail_host' => $request->mail_host,
                    'encryption_type' => $request->encryption_type,
                    'sender_email' => $request->sender_email,
                    'password' => $request->password,
                    'port' => $request->port,
                ]);
            } else {
                // Create new record (only once)
                MailSetting::create([
                    'app_name' => $request->app_name,
                    'mail_host' => $request->mail_host,
                    'encryption_type' => $request->encryption_type,
                    'sender_email' => $request->sender_email,
                    'password' => $request->password,
                    'port' => $request->port,
                ]);
            }
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Mail Settings Saved Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function sendTestMail(Request $request){
        $request->validate([
            'recipient_email' => 'required|email',
        ]);

        try {
            // Check if mail settings exist
            $mailSetting = MailSetting::first();

            if (!$mailSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mail settings not configured. Please configure mail settings first.'
                ], 400);
            }

            // Send test email
            Mail::to($request->recipient_email)->send(new TestMail());

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!'
            ]);

        } catch(\Exception $e) {
            Log::error('Test Mail Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

