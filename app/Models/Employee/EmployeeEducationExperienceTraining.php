<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeEducationExperienceTraining extends Model
{
    use Userstamps, Auditable;
    use HasFactory, OrganizationScoped;

    protected $table = 'employee_education_experience_training';

    protected $fillable = [
        'employee_id',
        'educations',
        'trainings',
        'status',
    ];

    protected $casts = [
        'educations' => 'array',
        'trainings' => 'array',
    ];

    /**
     * Relationship with Employee
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
};

