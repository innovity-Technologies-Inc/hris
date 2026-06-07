<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    protected $table = 'salary_grades';
    protected $fillable = ['grade_code', 'grade_name', 'status'];

}

