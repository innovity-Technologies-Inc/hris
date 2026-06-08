<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class GeneralSetting extends Model
{
    use Userstamps, Auditable;
    protected $table = 'general_settings';
    protected $fillable = [
        'name', 'currency', 'logo_light', 'logo_dark', 'favicon', 'branch_status', 'division_status',
        'department_status', 'section_status'
    ];

    /**
     * Get the full path to the light logo
     *
     * @return string|null
     */
    public function getLogoLightPathAttribute(): ?string
    {
        if (empty($this->logo_light)) {
            return null;
        }

        $path = storage_path('app/public/' . $this->logo_light);
        return file_exists($path) ? $path : null;
    }

    /**
     * Get the full path to the dark logo
     *
     * @return string|null
     */
    public function getLogoDarkPathAttribute(): ?string
    {
        if (empty($this->logo_dark)) {
            return null;
        }

        $path = storage_path('app/public/' . $this->logo_dark);
        return file_exists($path) ? $path : null;
    }

    /**
     * Get the primary logo path (prefers light logo)
     *
     * @return string|null
     */
    public function getLogoPathAttribute(): ?string
    {
        return $this->logo_light_path ?? $this->logo_dark_path;
    }

    /**
     * Get the light logo URL
     *
     * @return string|null
     */
    public function getLogoLightUrlAttribute(): ?string
    {
        return $this->logo_light ? asset('storage/' . $this->logo_light) : null;
    }

    /**
     * Get the dark logo URL
     *
     * @return string|null
     */
    public function getLogoDarkUrlAttribute(): ?string
    {
        return $this->logo_dark ? asset('storage/' . $this->logo_dark) : null;
    }

    /**
     * Get the favicon URL
     *
     * @return string|null
     */
    public function getFaviconUrlAttribute(): ?string
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }
}

