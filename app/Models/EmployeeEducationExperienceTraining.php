<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;

class EmployeeEducationExperienceTraining extends Model
{
    use HasFactory, OrganizationScoped;

    protected $table = 'employee_education_experience_training';

    protected $fillable = [
        'employee_id',
        'educations',
        'experiences',
        'trainings',
    ];

    protected $casts = [
        'educations' => 'array',
        'experiences' => 'array',
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
