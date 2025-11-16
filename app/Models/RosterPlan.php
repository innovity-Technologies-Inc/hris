<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RosterPlan extends Model
{
    protected $fillable = [
        'name', 'status', 'short_name', 'description', 'swapping', 'first_shift_id', 'second_shift_id'
    ];

    public function getFirstShift(){
        return $this->belongsTo(ShiftPlan::class, 'first_shift_id', 'id');
    }

    public function getSecondShift(){
        return $this->belongsTo(ShiftPlan::class, 'second_shift_id', 'id');
    }
}
