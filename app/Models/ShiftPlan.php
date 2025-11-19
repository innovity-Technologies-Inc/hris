<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'clock_in_time',
        'clock_out_time',
        'treat_as_full_day_minutes',
        'treat_as_half_day_minutes',
        'grace_time',
        'late_after_minutes',
        'excessive_late_after_minutes',
        'early_out_grace_minutes',
        'early_out_before',
        'breakfast_status',
        'breakfast_start_time',
        'breakfast_end_time',
        'lunch_status',
        'lunch_start_time',
        'lunch_end_time',
        'snacks_status',
        'snacks_start_time',
        'snacks_end_time',
        'dinner_status',
        'dinner_start_time',
        'dinner_end_time',
        'active_ind',
    ];

    // protected $casts = [
    //     'clock_in_time' => 'datetime:H:i',
    //     'clock_out_time' => 'datetime:H:i',
    //     'breakfast_start_time' => 'datetime:H:i',
    //     'breakfast_end_time' => 'datetime:H:i',
    //     'lunch_start_time' => 'datetime:H:i',
    //     'lunch_end_time' => 'datetime:H:i',
    //     'snacks_start_time' => 'datetime:H:i',
    //     'snacks_end_time' => 'datetime:H:i',
    //     'dinner_start_time' => 'datetime:H:i',
    //     'dinner_end_time' => 'datetime:H:i',
    // ];
}
