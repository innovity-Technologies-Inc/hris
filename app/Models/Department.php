<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'division_id',
        'department_name',
        'short_name',
        'job_number_code',
        'status',
    ];

    public function getDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
}
