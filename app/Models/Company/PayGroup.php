<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'payroll_frequency',
        'salary_processing_day',
        'status',
    ];
}
