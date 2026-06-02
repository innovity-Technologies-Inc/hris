<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'birthday_days',
        'visa_days',
        'work_permit_days',
        'passport_days',
        'license_days',
        'probation_days',
    ];

    protected $casts = [
        'birthday_days' => 'integer',
        'visa_days' => 'integer',
        'work_permit_days' => 'integer',
        'passport_days' => 'integer',
        'license_days' => 'integer',
        'probation_days' => 'integer',
    ];
}
