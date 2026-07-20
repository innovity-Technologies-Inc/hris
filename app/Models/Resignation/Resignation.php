<?php

namespace App\Models\Resignation;

use App\Models\Employee\Employee;
use App\Models\User;
use App\Traits\OrganizationScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Innovity\ApprovalEngine\Traits\Approvable;

class Resignation extends Model
{
    use HasFactory, SoftDeletes, OrganizationScoped, Approvable;

    protected $table = 'resignations';

    protected $fillable = [
        'employee_id',
        'resignation_date',
        'notice_period_days',
        'last_working_day',
        'reason',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'resignation_date' => 'date',
        'last_working_day' => 'date',
        'notice_period_days' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
