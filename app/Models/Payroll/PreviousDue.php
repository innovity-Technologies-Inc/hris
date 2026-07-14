<?php

namespace App\Models\Payroll;

use App\Traits\Userstamps;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreviousDue extends Model
{
    use Userstamps;
    protected $fillable = [
        'employee_id',
        'amount',
        'salary_month',
        'status',
        'reason',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
