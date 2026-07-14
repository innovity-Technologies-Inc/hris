<?php

namespace App\Models\Announcement;

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Department;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class Announcement extends Model
{
    use Userstamps, Auditable;
    use OrganizationScoped;

    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'content',
        'attachment_path',
        'company_id',
        'branch_id',
        'department_id',
        'created_by',
        'updated_by'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(CompanyLocation::class, 'branch_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
