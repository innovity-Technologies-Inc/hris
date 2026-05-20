<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
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

