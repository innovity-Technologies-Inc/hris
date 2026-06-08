<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class LeaveEncashmentPlan extends Model
{
    use Userstamps, Auditable;
    protected $table = 'leave_encashment_plans';

    protected $fillable = [
        'title',
        'description',
        'encashment_basis',
        'min_balance_to_maintain',
        'max_encashable_days_per_year',
        'encashment_rate',
        'status',
    ];
}
