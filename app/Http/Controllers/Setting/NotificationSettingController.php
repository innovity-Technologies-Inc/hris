<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\NotificationSettingRequest;
use App\Services\Setting\NotificationSettingServices;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
    protected $settingService;

    public function __construct(NotificationSettingServices $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Show the notification settings page
     */
    public function index()
    {
        $settings = $this->settingService->getSettings();
        return view('setting.notification_settings', compact('settings'));
    }

    /**
     * Store or update notification settings
     */
    public function store(NotificationSettingRequest $request)
    {
        try {
            $this->settingService->updateSettings($request->validated());
            return redirect()->back()->with('success', 'Notification settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update notification settings: ' . $e->getMessage());
        }
    }
}
