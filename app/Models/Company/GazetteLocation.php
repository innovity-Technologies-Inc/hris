<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class GazetteLocation extends Model
{
    use Userstamps, Auditable;
    protected $table = 'gazette_locations';
    protected $fillable = ['name', 'status'];
}

