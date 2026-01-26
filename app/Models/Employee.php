<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function roster(){
        return $this->hasMany(EmployeeRosterPlan::class, 'employee_id', 'id');
    }

    public function offDayPlan(){
        return $this->hasMany(EmployeeOffdayPlan::class, 'employee_id', 'id');
    }

    public function officeInfo(){
        return $this->hasOne(EmployeeOfficeInfo::class, 'employee_id', 'id');
    }

    public function salaryBreakdown(){
        return $this->hasOne(EmployeeSalaryBreakdown::class, 'employee_id', 'id');
    }
}
