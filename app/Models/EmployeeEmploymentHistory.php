<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEmploymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'histories',
        'status',
    ];

    protected $casts = [
        'histories' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
