<?php

namespace App\Models\Payroll;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disbursement extends Model
{
    protected $fillable = [
        'process_id',
        'batch_id',
        'process_type',
        'payment_method',
        'total_amount',
        'total_employees',
        'note',
        'disbursed_by',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(PayrollProcess::class, 'process_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DisbursementItem::class, 'disbursement_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(DisbursementAttachment::class, 'disbursement_id');
    }

    public function disbursedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }
}
