<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

use App\Models\User;
use App\Traits\OrganizationScoped;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Employee extends Model
{
    use Userstamps, Auditable;
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
        'nid',
        'is_nid_verified',
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
        'general_info_status',
    ];

    protected $casts = [
        'present_address' => 'array',
        'permanent_address' => 'array',
        'reference_address' => 'array',
        'is_nid_verified' => 'boolean',
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
     * Get the employee's first name with fallback to full_name or user name.
     */
    public function getFirstNameAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        if (!empty($this->attributes['full_name'] ?? null)) {
            $parts = explode(' ', trim($this->attributes['full_name']), 2);
            return $parts[0];
        }

        if ($this->user && !empty($this->user->name)) {
            $parts = explode(' ', trim($this->user->name), 2);
            return $parts[0];
        }

        return $value;
    }

    /**
     * Get the employee's last name with fallback to full_name or user name.
     */
    public function getLastNameAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        if (!empty($this->attributes['full_name'] ?? null)) {
            $parts = explode(' ', trim($this->attributes['full_name']), 2);
            return $parts[1] ?? null;
        }

        if ($this->user && !empty($this->user->name)) {
            $parts = explode(' ', trim($this->user->name), 2);
            return $parts[1] ?? null;
        }

        return $value;
    }

    /**
     * Get the employee's work email with fallback to user email.
     */
    public function getWorkEmailAttribute($value): ?string
    {
        if (!empty($value)) {
            return $value;
        }

        if ($this->user && !empty($this->user->email)) {
            return $this->user->email;
        }

        return $value;
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

    public function bankAccount()
    {
        return $this->hasOne(EmployeeBankAccount::class, 'employee_id', 'id');
    }
    public function leaveApplications()
    {
        return $this->hasMany(\App\Models\Leave\Leave::class, 'employee_id', 'id');
    }

    public function leaveBalances()
    {
        return $this->hasMany(\App\Models\Leave\LeaveCount::class, 'employee_id', 'id');
    }

    public function assignedLeavePlans()
    {
        return $this->hasMany(\App\Models\Employee\EmployeeLeavePlan::class, 'employee_id', 'id')->where('status', 'active');
    }

    public function lifecycles()
    {
        return $this->hasMany(EmployeeLifecycle::class, 'employee_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id', 'id');
    }

    public function getProfileCompletionDetailsAttribute()
    {
        $calculateModelCompletion = function($model, $modelClass) {
            $temp = $model ?: new $modelClass();
            $fields = $temp->getFillable();
            $exclude = ['id', 'employee_id', 'created_at', 'updated_at', 'status', 'general_info_status', 'user_id', 'review_cause'];
            $fields = array_values(array_diff($fields, $exclude));
            
            $totalFields = count($fields);
            if ($totalFields === 0) {
                return ['total' => 1, 'filled' => $model ? 1 : 0, 'percentage' => $model ? 100 : 0];
            }

            $filled = 0;
            if ($model) {
                foreach ($fields as $field) {
                    $val = $model->getAttribute($field);
                    if ($val !== null && $val !== '' && (is_array($val) ? count($val) > 0 : true) && $val !== '[]') {
                        $filled++;
                    }
                }
            }
            
            return [
                'total' => $totalFields,
                'filled' => $filled,
                'percentage' => round(($filled / $totalFields) * 100)
            ];
        };

        $sections = [
            'General' => $calculateModelCompletion($this, \App\Models\Employee\Employee::class),
            'Education' => $calculateModelCompletion($this->educationInfo()->first(), \App\Models\Employee\EmployeeEducationExperienceTraining::class),
            'Employment History' => $calculateModelCompletion($this->employmentHistory()->first(), \App\Models\Employee\EmployeeEmploymentHistory::class),
            'Emergency Contact' => $calculateModelCompletion($this->nomineeInfo()->first(), \App\Models\Employee\EmployeeNominee::class),
            'Office' => $calculateModelCompletion($this->officeInfo()->first(), \App\Models\Employee\EmployeeOfficeInfo::class),
            'Policy Tag' => $calculateModelCompletion($this->employeeEligibility()->first(), \App\Models\Employee\EmployeeEligiblePlan::class),
            'Salary Breakdown' => $calculateModelCompletion($this->salaryBreakdown()->first(), \App\Models\Employee\EmployeeSalaryBreakdown::class),
            'Accounts' => $calculateModelCompletion($this->bankAccount()->first(), \App\Models\Employee\EmployeeBankAccount::class),
        ];

        $totalPercentage = 0;
        foreach ($sections as $section) {
            $totalPercentage += $section['percentage'];
        }
        $averagePercentage = round($totalPercentage / count($sections));

        return [
            'sections' => $sections,
            'average_percentage' => $averagePercentage
        ];
    }

    public function getProfileCompletionPercentageAttribute()
    {
        return $this->profile_completion_details['average_percentage'];
    }
}
