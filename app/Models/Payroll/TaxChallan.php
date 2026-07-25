<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;
use App\Traits\OrganizationScoped;
use App\Models\Employee\Employee;
use App\Models\Company\Company;

class TaxChallan extends Model
{
    use Userstamps, Auditable, OrganizationScoped;

    protected $table = 'tax_challans';

    protected $fillable = [
        'employee_id',
        'company_id',
        'tax_paid_from',
        'tax_paid_to',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
