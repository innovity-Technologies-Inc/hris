<?php

namespace App\Models\Organization;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Userstamps;
use App\Traits\Auditable;

class Organization extends Model
{
    use Userstamps, Auditable;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'email',
        'phone',
        'address',
        'status',
    ];

    /**
     * Get the companies for the organization.
     */
    public function companies()
    {
        return $this->hasMany(\App\Models\Company\Company::class, 'organization_id');
    }

    /**
     * Get the users for the organization.
     */
    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'organization_id');
    }
}
