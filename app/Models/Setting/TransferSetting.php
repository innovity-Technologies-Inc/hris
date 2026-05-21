<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class TransferSetting extends Model
{
    protected $fillable = [
        'employee_transfer_level',
        'supervisor_transfer_level',
    ];
}
