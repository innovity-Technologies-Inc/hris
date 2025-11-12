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
        'overtime_rate_type',
        'overtime_rate',
        'minimum_overtime_hours',
        'maximum_overtime_hours',
        'overtime_start_time',
        'overtime_end_time',
        'max_ot_limit',
        'max_ot_period',
        'notes',
        'active_ind',
    ];

    protected $casts = [
        'overtime_rate' => 'decimal:2',
        'minimum_overtime_hours' => 'decimal:2',
        'maximum_overtime_hours' => 'decimal:2',
        'max_ot_limit' => 'decimal:2',
        'overtime_start_time' => 'datetime:H:i',
        'overtime_end_time' => 'datetime:H:i',
    ];
}
