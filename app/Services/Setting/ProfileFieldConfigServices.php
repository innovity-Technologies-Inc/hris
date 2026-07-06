<?php

namespace App\Services\Setting;

use App\Models\Setting\ProfileFieldConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProfileFieldConfigServices
{
    /**
     * Section display labels.
     */
    private array $sectionLabels = [
        'general' => 'General Information',
        'office-information' => 'Office Information',
        'employee-policy' => 'Employee Policy (Eligible Plans)',
        'education' => 'Education & Training',
        'employment_history' => 'Employment History',
        'emergency_contact' => 'Emergency Contact / Nominee',
        'salary-breakdown' => 'Salary Breakdown',
        'employee-bank-account' => 'Bank Account',
    ];

    /**
     * Get all profile field configurations grouped by section.
     */
    public function getGroupedConfigs(): Collection
    {
        return ProfileFieldConfig::orderByRaw("FIELD(section, 'general', 'office-information', 'employee-policy', 'education', 'employment_history', 'emergency_contact', 'salary-breakdown', 'employee-bank-account')")
            ->orderBy('id')
            ->get()
            ->groupBy('section');
    }

    /**
     * Get section labels map.
     */
    public function getSectionLabels(): array
    {
        return $this->sectionLabels;
    }

    /**
     * Save profile field configuration.
     */
    public function saveConfig(array $requiredFieldIds): void
    {
        try {
            // Set all to optional first, then mark selected as required
            ProfileFieldConfig::query()->update(['is_required' => false]);

            if (!empty($requiredFieldIds)) {
                ProfileFieldConfig::whereIn('id', $requiredFieldIds)->update(['is_required' => true]);
            }

            // Clear cached configs
            cache()->forget('profile_field_configs');
        } catch (\Exception $e) {
            Log::error('Profile Field Config Save Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
