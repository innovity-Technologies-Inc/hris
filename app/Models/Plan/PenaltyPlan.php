<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class PenaltyPlan extends Model
{
    use Userstamps, Auditable;
    protected $table = 'penalty_plans';

    protected $fillable = [
        'title',
        'description',
        'penalty_amount',
        'status',
    ];
}
