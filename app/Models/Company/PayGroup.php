<?php

namespace App\Models\Company;

use App\Traits\OrganizationScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayGroup extends Model
{
    use HasFactory, OrganizationScoped;

    protected $fillable = [
        'current_company_id',
        'title',
        'payroll_frequency',
        'salary_processing_day',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }
}
