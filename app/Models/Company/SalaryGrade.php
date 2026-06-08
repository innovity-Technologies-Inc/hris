<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

use App\Models\Company\PayScale;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class SalaryGrade extends Model
{
    use Userstamps, Auditable;
    protected $table = 'salary_grades';
    protected $fillable = ['grade_code', 'grade_name', 'status'];

    public function payScales()
    {
        return $this->hasMany(PayScale::class, 'grade_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($salaryGrade) {
            // Delete related pay scales
            $salaryGrade->payScales()->each(function ($payScale) {
                $payScale->delete();
            });
        });
    }
}

