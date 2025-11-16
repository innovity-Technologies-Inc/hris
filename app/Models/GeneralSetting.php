<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    protected $fillable = [
        'name', 'currency', 'logo_light', 'logo_dark', 'favicon', 'branch_status', 'division_status',
        'department_status', 'section_status'
    ];
}
