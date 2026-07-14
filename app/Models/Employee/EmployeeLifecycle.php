<?php

namespace App\Models\Employee;

use App\Traits\Userstamps;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLifecycle extends Model
{
    use Userstamps;
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'status_date',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
