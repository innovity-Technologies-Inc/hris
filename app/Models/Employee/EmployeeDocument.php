<?php

namespace App\Models\Employee;

use App\Traits\Userstamps;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use Userstamps;
    protected $fillable = [
        'employee_id',
        'title',
        'file_path',
        'file_type',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
