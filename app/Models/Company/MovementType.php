<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class MovementType extends Model
{
    use Userstamps, Auditable;

    protected $table = 'movement_types';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
