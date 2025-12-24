<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OffDayPlan extends Model
{
    protected $table = 'off_day_plans';
    
    protected $fillable = [
        'name',
        'short_name',
        'start_time',
        'end_time',
        'grace_time',
        'grace_time_before',
        
        // Configuration fields (refactored to match OT Plan pattern)
        'offday_config_type',
        'salary_rate_type',
        'offday_multiplier',
        'custom_offday_rate',
        
        'status'
    ];

    /**
     * Calculate offday remuneration for an employee based on configuration type
     * 
     * @param float $basicSalary Employee's basic salary
     * @param float $hoursWorked Hours worked on offday
     * @return float Calculated remuneration amount
     */
    public function calculateRemuneration($basicSalary, $hoursWorked = 8)
    {
        if ($this->offday_config_type === 'Salary Based') {
            return $this->calculateSalaryBasedRemuneration($basicSalary, $hoursWorked);
        }
        
        // Custom rate: fixed amount per hour
        return $this->custom_offday_rate * $hoursWorked;
    }

    /**
     * Calculate salary-based remuneration
     * 
     * @param float $basicSalary Employee's basic salary
     * @param float $hoursWorked Hours worked on offday
     * @return float Calculated remuneration amount
     */
    private function calculateSalaryBasedRemuneration($basicSalary, $hoursWorked)
    {
        // Calculate hourly rate from monthly basic salary
        // Assuming 208 working hours per month (26 days * 8 hours)
        $hourlyRate = $basicSalary / 208;

        if ($this->salary_rate_type === 'Basic Rate') {
            // Basic Rate: Use hourly rate as-is
            return $hourlyRate * $hoursWorked;
        }
        
        // Multiplier: Apply multiplier to hourly rate
        return $hourlyRate * $this->offday_multiplier * $hoursWorked;
    }

    /**
     * Get human-readable configuration type
     * 
     * @return string
     */
    public function getConfigurationDescription()
    {
        if ($this->offday_config_type === 'Salary Based') {
            if ($this->salary_rate_type === 'Basic Rate') {
                return 'Salary Based - Basic Rate';
            }
            return "Salary Based - {$this->offday_multiplier}x Multiplier";
        }
        
        return "Custom Rate - " . number_format($this->custom_offday_rate, 2) . " per hour";
    }
}
