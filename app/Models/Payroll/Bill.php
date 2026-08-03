<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee\Employee;
use App\Traits\OrganizationScoped;
use App\Traits\Userstamps;

class Bill extends Model
{
    use Userstamps;
    use OrganizationScoped;

    protected $fillable = [
        'employee_id',
        'expense_id',
        'type',
        'expense_type',
        'amount',
        'payment_status',
        'payment_method',
        'remarks',
        'attachment_path',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
