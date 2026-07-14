<?php

namespace App\Models\ClaimExpense;

use App\Models\Employee\Employee;
use App\Traits\Auditable;
use App\Traits\OrganizationScoped;
use App\Traits\Userstamps;
use Innovity\ApprovalEngine\Traits\Approvable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseApplication extends Model
{
    use Userstamps, Auditable, Approvable;
    use OrganizationScoped;

    protected $table = 'expense_applications';

    protected $fillable = [
        'employee_id',
        'expense_type_id',
        'amount',
        'payment_method',
        'purpose',
        'receipt_path',
        'status',
        'approval_count_required',
        'current_approval_count',
        'remarks',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }
}
