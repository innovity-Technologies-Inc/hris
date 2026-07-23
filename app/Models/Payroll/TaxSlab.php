<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class TaxSlab extends Model
{
    use Userstamps, Auditable;

    protected $fillable = [
        'tax_policy_id',
        'min_amount',
        'max_amount',
        'tax_percentage',
        'tax_amount',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function policy()
    {
        return $this->belongsTo(TaxPolicy::class, 'tax_policy_id');
    }
}
