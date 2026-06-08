<?php

namespace App\Models\Company;

use App\Models\Structure\OrganizationStructure;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Group extends Model
{
    use Userstamps, Auditable;
    protected $table = 'groups';
    protected $fillable = ['name', 'status'];

    public function companies()
    {
        return $this->hasMany(Company::class, 'group_id');
    }

    public function organizationStructures()
    {
        return $this->hasMany(OrganizationStructure::class, 'group_id');
    }
}
