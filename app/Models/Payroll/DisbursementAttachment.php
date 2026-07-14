<?php

namespace App\Models\Payroll;

use App\Traits\Userstamps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisbursementAttachment extends Model
{
    use Userstamps;
    protected $fillable = [
        'disbursement_id',
        'file_path',
        'original_name',
    ];

    public function disbursement(): BelongsTo
    {
        return $this->belongsTo(Disbursement::class, 'disbursement_id');
    }
}
