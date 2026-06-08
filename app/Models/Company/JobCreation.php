<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class JobCreation extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'designation_id',
        'department_id',
        'job_ind',
        'display_designation',
        'display_serial',
        'status',
        'remarks',
    ];
    public function getDesignation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }
    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');  
    }
}

