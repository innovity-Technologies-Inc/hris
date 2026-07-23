<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;
use App\Models\Employee\Employee;

class TaxCalculation extends Model
{
    use Userstamps, Auditable;

    protected $table = 'tax_calculations';

    protected $fillable = [
        'employee_id',
        'policy_id',
        'gross_salary',
        'exemption_amount',
        'taxable_amount',
        'slab_taxes',
        'slabs_reached',
        'total_tax_amount',
    ];

    protected $casts = [
        'slab_taxes' => 'array',
        'gross_salary' => 'decimal:2',
        'exemption_amount' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'total_tax_amount' => 'decimal:2',
        'slabs_reached' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function policy()
    {
        return $this->belongsTo(TaxPolicy::class, 'policy_id');
    }
}
