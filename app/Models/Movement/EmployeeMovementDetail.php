<?php

namespace App\Models\Movement;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class EmployeeMovementDetail extends Model
{
    use Userstamps, Auditable;

    protected $table = 'employee_movement_details';

    protected $fillable = [
        'employee_movement_id',
        'source_address',
        'source_lat',
        'source_lng',
        'destination_address',
        'dest_lat',
        'dest_lng',
        'distance',
        'reason',
        'attachment_path',
    ];

    public function movement()
    {
        return $this->belongsTo(EmployeeMovement::class, 'employee_movement_id');
    }
}
