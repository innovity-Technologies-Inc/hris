<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
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

