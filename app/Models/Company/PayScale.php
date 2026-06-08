<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class PayScale extends Model
{
    use Userstamps, Auditable;
    use HasFactory;

    protected $fillable = [
        'title',
        'grade_id',
        'pay_group_id',
        'min_salary',
        'max_salary',
        'status',
    ];

    public function grade()
    {
        return $this->belongsTo(SalaryGrade::class, 'grade_id');
    }

    public function payGroup()
    {
        return $this->belongsTo(PayGroup::class, 'pay_group_id');
    }
}
