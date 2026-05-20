<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    protected $table = 'salary_grades';
    protected $fillable = ['name', 'tofsil_id', 'status'];

    public function getTofsil(){
        return $this->belongsTo(Tofsil::class, 'tofsil_id', 'id');
    }
}

