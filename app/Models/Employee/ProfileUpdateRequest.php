<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

use App\Traits\OrganizationScoped;

class ProfileUpdateRequest extends Model
{
    use \Innovity\ApprovalEngine\Traits\Approvable;
    use OrganizationScoped;

    protected $fillable = [
        'employee_id',
        'section',
        'previous_data',
        'requested_data',
        'status',
    ];

    protected $casts = [
        'previous_data' => 'array',
        'requested_data' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
