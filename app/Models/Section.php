<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'division_id',
        'department_id',
        'section_name',
        'short_name',
        'status',
    ];

    public function getDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }

    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
