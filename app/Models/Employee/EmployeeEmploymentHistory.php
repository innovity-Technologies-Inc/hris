<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;
use App\Traits\OrganizationScoped;

class EmployeeEmploymentHistory extends Model
{
    use Userstamps, Auditable;
    use HasFactory, OrganizationScoped;

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
