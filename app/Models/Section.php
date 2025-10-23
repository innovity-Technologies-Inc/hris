<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'department_id',
        'section_name',
        'short_name',
        'status',
    ];

    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
