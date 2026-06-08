<?php

namespace App\Models\Plan;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class OTPlan extends Model
{
    use Userstamps, Auditable;
    use HasFactory;

    protected $table = 'ot_plans';

    protected $fillable = [
        'name',
        'description',
        'ot_config_type',
        'salary_rate_type',
        'overtime_multiplier',
        'custom_overtime_rate',
        'maximum_overtime',
        'status',
    ];
}

