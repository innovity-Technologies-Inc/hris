<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class MealPlan extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'name',
        'type',
        'status',
        'cost',
        'description',
        'start_time',
        'end_time',
    ];
}

