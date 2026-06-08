<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class LeavePlan extends Model
{
    use Userstamps, Auditable;
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'applicable_gender',
        'day_type',
        'leave_type',
        'leave_limit',
        'max_no_of_days',
        'display_serial',
        'apply_limit',
        'allow_fractional_leave',
        'off_day_include',
        'active_ind',
    ];
}

