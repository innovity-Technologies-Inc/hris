<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MovementAttachment extends Model
{
    protected $table = 'movement_attachments';

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_path',
        'file_name',
    ];

    /**
     * Get the parent attachable model (Transfer, Promotion, Demotion, Increment, Decrement).
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
