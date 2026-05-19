<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Traits\OrganizationScoped;

class Employee extends Model
{
    use HasFactory, OrganizationScoped;

    protected $fillable = [
        'user_id',
        'applicant_id',
        'system_id',
        'punch_card_no',
        'first_name',
        'last_name',
        'middle_name',
        'full_name',
        'father_name',
        'mother_name',
        'spouse_name',
        'marital_status',
        'gender',
        'religion',
        'nationality',
        'blood_group',
        'height_feet',
        'height_inches',
        'children_count',
        'tin',
        'passport_no',
        'passport_expiry',
        'license_no',
        'license_expiry',
        'visa_expiry',
        'work_expiry',
        'residency_id_number',
        'date_of_birth',
        'birth_country',
        'birth_reg_no',
        'personal_mobile',
        'home_phone',
        'work_mobile',
        'work_phone',
        'work_email',
        'personal_email',
        'photo_path',
        'fingerprint_path',
        'signature_path',
        'experience_attachment_path',
        'present_address',
        'permanent_address',
        'reference_address',
        'review_cause',
        'status',
    ];

    protected $casts = [
        'present_address' => 'array',
        'permanent_address' => 'array',
        'reference_address' => 'array',
    ];

    public function shift(){
        return $this->hasMany(EmployeeShiftPlan::class, 'employee_id', 'id')
            ->where('status', '=', 'active');
    }

    public function salary(){
        return $this->hasOne(EmployeeSalaryBreakdown::class, 'employee_id', 'id');
    }

    public function employeeEligibility(){
        return $this->hasOne(EmployeeEligiblePlan::class, 'employee_id', 'id');
    }

    public function roster(){
        return $this->hasMany(EmployeeRosterPlan::class, 'employee_id', 'id');
    }

    public function offDayPlan(){
        return $this->hasMany(EmployeeOffdayPlan::class, 'employee_id', 'id');
    }

    public function officeInfo(){
        return $this->hasOne(EmployeeOfficeInfo::class, 'employee_id', 'id');
    }

    public function educationInfo(){
        return $this->hasOne(EmployeeEducationExperienceTraining::class, 'employee_id', 'id');
    }

    public function nomineeInfo(){
        return $this->hasOne(EmployeeNominee::class, 'employee_id', 'id');
    }

    public function employmentHistory(){
        return $this->hasOne(EmployeeEmploymentHistory::class, 'employee_id', 'id');
    }

    public function salaryBreakdown(){
        return $this->hasOne(EmployeeSalaryBreakdown::class, 'employee_id', 'id');
    }

    /**
     * Get all ID cards for this employee
     */
    public function employeeIdCards(): HasMany
    {
        return $this->hasMany(EmployeeId::class, 'employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the active ID card for this employee
     */
    public function activeIdCard(): HasOne
    {
        return $this->hasOne(EmployeeId::class, 'employee_id', 'id')
            ->where('status', 'active');
    }

    /**
     * Check if employee has an active ID card
     */
    public function hasActiveIdCard(): bool
    {
        return $this->activeIdCard()->exists();
    }

    /**
     * Get the active ID card or null
     */
    public function getActiveIdCard(): ?EmployeeId
    {
        return $this->activeIdCard()->first();
    }
}
