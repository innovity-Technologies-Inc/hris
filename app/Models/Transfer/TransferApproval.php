<?php

namespace App\Models\Transfer;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class TransferApproval extends Model
{
    use Userstamps, Auditable;
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'approver_id',
        'status',
        'remarks',
        'approved_at',
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
