<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffDayPlan extends Model
{
    protected $table = 'off_day_plans';

    protected $fillable = [
        'name',
        'short_name',
        'start_time',
        'end_time',
        'grace_time',
        'grace_time_before',

        // Configuration fields (refactored to match OT Plan pattern)
        'offday_config_type',
        'salary_rate_type',
        'offday_multiplier',
        'custom_offday_rate',

        'status'
    ];
}
