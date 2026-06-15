<?php

namespace App\Models\Payroll;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arrear extends Model
{
    protected $fillable = [
        'process_id',
        'employee_id',
        'batch_id',
        'amount',
        'type',
        'payment_month',
        'reason',
        'status',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(PayrollProcess::class, 'process_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
