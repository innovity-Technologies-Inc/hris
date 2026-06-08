<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class OffDayPlan extends Model
{
    use Userstamps, Auditable;
    protected $table = 'off_day_plans';

    protected $fillable = [
        'name',
        'short_name',
        'shift_id', // Reference to shift for timing configuration

        // Configuration fields (refactored to match OT Plan pattern)
        'offday_config_type',
        'salary_rate_type',
        'offday_multiplier',
        'custom_offday_rate',

        'status'
    ];

    /**
     * Get the shift associated with this off-day plan.
     * Timing (start_time, end_time, grace_time) is derived from the shift.
     */
    public function getShift()
    {
        return $this->belongsTo(ShiftPlan::class, 'shift_id', 'id');
    }


}

