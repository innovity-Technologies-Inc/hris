<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeExperience extends Model
{
    use HasFactory;

    protected $table = 'employee_experiences';

    protected $fillable = [
        'employee_id',
        'company',
        'designation',
        'department',
        'date_from',
        'date_to',
        'duration',
        'responsibility',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
