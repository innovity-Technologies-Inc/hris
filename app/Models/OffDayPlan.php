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
        'remuneration',
        'status'
    ];
}
