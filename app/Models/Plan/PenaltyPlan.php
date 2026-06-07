<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

class PenaltyPlan extends Model
{
    protected $table = 'penalty_plans';

    protected $fillable = [
        'title',
        'description',
        'penalty_amount',
        'status',
    ];
}
