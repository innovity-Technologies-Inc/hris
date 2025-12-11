<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionPlan extends Model
{
    use HasFactory;

    protected $table = 'deduction_plans';

    protected $fillable = [
        'late_deduction_days',
        'late_salary_deduction_rate',
        'early_out_deduction_days',
        'early_out_salary_deduction_rate',
        'excessive_late_deduction_days',
        'excessive_late_salary_deduction_rate',
        'absent_deduction_days',
        'absent_salary_deduction_rate',
        'calculation_type',
    ];


}
