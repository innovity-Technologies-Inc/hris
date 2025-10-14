<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeInfoEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'emp_type', 'paygrade', 'category', 'hr_file_no', 'totali', 'file_note',
        'joining_company_id', 'joining_business_unit_id', 'joining_division_id', 'joining_department_id',
        'joining_designation_id', 'joining_section_id', 'joining_subsection', 'joining_floor', 'date_of_join',
        'current_company_id', 'current_business_unit_id', 'current_division_id', 'current_department_id',
        'current_designation_id', 'current_section_id', 'current_subsection', 'current_floor', 'current_info_effective_date',
        'orientation_required', 'orientation_from', 'orientation_to', 'orientation_type', 'orientation_days',
        'confirmation_date', 'probation_duration', 'next_promotion_date', 'promotion_cycle', 'increment_cycle',
        'weekends', 'alternate_off_day',
        'ot_allowed', 'pf_eligible', 'salary_type', 'imprest_fund', 'transport_eligible', 'status',
        'can_apply_loan', 'pf_effective_date', 'cash_collector', 'can_apply_advance', 'gratuity_eligible', 'separation_type'
    ];

    protected $casts = [
        'weekends' => 'array',
        'date_of_join' => 'date',
        'current_info_effective_date' => 'date',
        'orientation_from' => 'date',
        'orientation_to' => 'date',
        'confirmation_date' => 'date',
        'next_promotion_date' => 'date',
        'pf_effective_date' => 'date',
    ];

    /**
     * Get the employee that this office information belongs to.
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
