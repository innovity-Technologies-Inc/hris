<?php

namespace App\Models\Payroll;

use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeePromotion extends Model
{
    protected $fillable = [
        'employee_id',
        'previous_designation',
        'new_designation',
        'increment_base',
        'increment_method',
        'increment_amount',
        'effective_from',
        'effective_to',
        'status',
    ];

    protected $casts = [
        'effective_from' => 'date',
        'effective_to' => 'date',
        'increment_amount' => 'decimal:2',
    ];

    /**
     * Get the employee associated with this promotion.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Get the previous designation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getPreviousDesignation()
    {
        return $this->belongsTo(Designation::class, 'previous_designation', 'id');
    }

    /**
     * Get the new designation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getNewDesignation()
    {
        return $this->belongsTo(Designation::class, 'new_designation', 'id');
    }

    /**
     * Get status badge color class.
     *
     * @return string
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-light',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get formatted increment summary.
     *
     * @return string
     */
    public function getIncrementSummary()
    {
        $base = ucfirst(str_replace('_', ' ', $this->increment_base));
        $method = ucfirst($this->increment_method);
        $amount = $this->increment_method === 'percentage'
            ? $this->increment_amount . '%'
            : '৳' . number_format($this->increment_amount, 2);

        return "{$amount} ({$method} on {$base})";
    }
}
