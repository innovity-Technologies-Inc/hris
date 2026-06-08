<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class DAPlan extends Model
{
    use Userstamps, Auditable;
    use HasFactory;

    protected $table = 'da_plans';

    protected $fillable = [
        'name',
        'short_name',
        'remuneration',
        'status',
    ];
}

