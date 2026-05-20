<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\OrganizationScoped;

class EmployeeNominee extends Model
{
    use HasFactory, OrganizationScoped;

    protected $table = 'employee_nominees';

    protected $fillable = [
        'employee_id',
        'nominee_name',
        'father_name',
        'mother_name',
        'spouse_name',
        'gender',
        'date_of_birth',
        'religion',
        'marital_status',
        'nationality',
        'blood_group',
        'photo_path',
        'nid',
        'birth_reg_no',
        'phone',
        'mobile',
        'present_address_line',
        'village',
        'post_office',
        'thana',
        'district',
        'state',
        'zip_code',
        'country',
        'status',
    ];

//    protected $casts = [
//        'date_of_birth' => 'date',
//        'ratio' => 'decimal:2',
//    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}

