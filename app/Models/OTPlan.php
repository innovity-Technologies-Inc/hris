<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OTPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ot_plans';

    protected $fillable = [
        'name',
        'description',
        'ot_type',
        'ot_config_type',
        'salary_rate_type',
        'overtime_multiplier',
        'custom_overtime_rate',
        'minimum_overtime_hours',
        'maximum_overtime_hours',
        'overtime_start_time',
        'overtime_end_time',
        'active_ind',
    ];
}
