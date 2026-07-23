<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class TaxPolicy extends Model
{
    use Userstamps, Auditable;

    protected $fillable = [
        'zero_tax_male',
        'zero_tax_female',
        'min_tax_amount',
        'exemption_type',
        'salary_ratio',
        'fixed_amount',
        'exempt_allowances',
    ];

    protected $casts = [
        'exempt_allowances' => 'array',
        'zero_tax_male' => 'decimal:2',
        'zero_tax_female' => 'decimal:2',
        'min_tax_amount' => 'decimal:2',
        'fixed_amount' => 'decimal:2',
    ];

    public function slabs()
    {
        return $this->hasMany(TaxSlab::class, 'tax_policy_id')->orderBy('taxable_amount', 'asc');
    }
}
