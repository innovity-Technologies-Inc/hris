<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class TransferSetting extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'employee_transfer_level',
        'supervisor_transfer_level',
    ];
}
