<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class BonusPlan extends Model
{
    use Userstamps, Auditable;
    protected $table = 'bonus_plans';
    protected $fillable = [
        'pay_group_id',
        'name',
        'description',
        'bonus_type',
        'bonus_config_type',
        'salary_rate_type',
        'multiplier',
        'custom_rate',
        'status',
    ];

    public function payGroup()
    {
        return $this->belongsTo(\App\Models\Company\PayGroup::class, 'pay_group_id');
    }

}

