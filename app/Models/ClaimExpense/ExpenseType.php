<?php

namespace App\Models\ClaimExpense;

use App\Models\Company\Company;
use App\Traits\Auditable;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseType extends Model
{
    use Userstamps, Auditable;

    protected $table = 'expense_types';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'status',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ExpenseApplication::class, 'expense_type_id');
    }
}
