<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOffdayPlan extends Model
{
    protected $table = 'employee_offday_plans';

    protected $fillable = [
        'employee_id',
        'plan_id',
        'from',
        'to',
        'status',
    ];
    
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    
    public function getPlan()
    {
        return $this->belongsTo(OffDayPlan::class, 'plan_id', 'id');
    }

    /**
     * Calculate remuneration for this employee's offday work
     * 
     * @param float $hoursWorked Hours worked on the offday (default: 8)
     * @return float Calculated remuneration amount
     */
    public function calculateRemuneration($hoursWorked = 8)
    {
        $employee = $this->getEmployee;
        $plan = $this->getPlan;

        if (!$employee || !$plan) {
            return 0;
        }

        // Get employee's basic salary
        // Assuming the Employee model has a basic_salary attribute or method
        $basicSalary = $employee->basic_salary ?? 0;

        // Use the plan's calculation method
        return $plan->calculateRemuneration($basicSalary, $hoursWorked);
    }

    /**
     * Get human-readable remuneration description
     * 
     * @return string
     */
    public function getRemunerationDescription()
    {
        $plan = $this->getPlan;
        return $plan ? $plan->getConfigurationDescription() : 'N/A';
    }
}
