<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class RosterPlan extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'name', 'short_name', 'swapping', 'description', 'status', 'first_shift_id', 'second_shift_id', 'third_shift_id'
    ];

    public function getFirstShift(){
        return $this->belongsTo(ShiftPlan::class, 'first_shift_id', 'id');
    }

    public function getSecondShift(){
        return $this->belongsTo(ShiftPlan::class, 'second_shift_id', 'id');
    }

    public function getThirdShift(){
        return $this->belongsTo(ShiftPlan::class, 'third_shift_id', 'id');
    }
}

