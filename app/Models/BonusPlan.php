<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusPlan extends Model
{
    protected $table = 'bonus_plans';
    protected $fillable = [
        'name',
        'description',
        'bonus_type',
        'bonus_config_type',
        'salary_rate_type',
        'overtime_multiplier',
        'custom_overtime_rate',
        'status',
    ];
    
}
