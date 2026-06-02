<?php

namespace App\Services\Setting;

use App\Models\Setting\NotificationSetting;
use Illuminate\Support\Facades\Log;

class NotificationSettingServices
{
    /**
     * Get the current notification settings
     */
    public function getSettings(): NotificationSetting
    {
        return NotificationSetting::first() ?? new NotificationSetting();
    }

    /**
     * Update or create notification settings
     */
    public function updateSettings(array $data): NotificationSetting
    {
        try {
            $settings = NotificationSetting::first();
            
            if ($settings) {
                $settings->update($data);
            } else {
                $settings = NotificationSetting::create($data);
            }

            return $settings;
        } catch (\Exception $e) {
            Log::error("Failed to update notification settings: " . $e->getMessage());
            throw $e;
        }
    }
}
