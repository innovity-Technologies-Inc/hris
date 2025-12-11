<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionPlan extends Model
{
    use HasFactory;

    protected $table = 'deduction_plans';

    protected $fillable = [
        'late_deduction',
        'early_out_deduction',
        'excessive_late_deduction',
        'status',
    ];

}
