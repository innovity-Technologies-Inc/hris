<?php

namespace App\Models\Payroll;

use App\Traits\Userstamps;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisbursementItem extends Model
{
    use Userstamps;
    protected $fillable = [
        'disbursement_id',
        'employee_id',
        'record_id',
        'amount',
    ];

    public function disbursement(): BelongsTo
    {
        return $this->belongsTo(Disbursement::class, 'disbursement_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
