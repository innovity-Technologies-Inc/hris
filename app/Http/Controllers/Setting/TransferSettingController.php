<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\TransferSetting;
use Illuminate\Http\Request;

class TransferSettingController extends Controller
{
    public function index()
    {
        $title = 'Career Movement Settings';
        $section = 'Settings';
        $sub_section = 'Career Movement Settings';
        $setting = TransferSetting::first();

        return view('setting.transfer.index', compact('title', 'section', 'sub_section', 'setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'employee_transfer_level' => 'required|string',
            'supervisor_transfer_level' => 'required|string',
        ]);

        $setting = TransferSetting::first();
        $setting->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Career Movement settings updated successfully.'
        ]);
    }
}
