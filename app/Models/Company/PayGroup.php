<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company\PayScale;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class PayGroup extends Model
{
    use Userstamps, Auditable;
    use HasFactory;

    protected $fillable = [
        'title',
        'payroll_frequency',
        'working_hours_per_day',
        'working_days_per_cycle',
        'salary_processing_day',
        'status',
    ];

    public function payScales()
    {
        return $this->hasMany(PayScale::class, 'pay_group_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($payGroup) {
            // Delete related pay scales
            $payGroup->payScales()->each(function ($payScale) {
                $payScale->delete();
            });
        });
    }
}
