<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $fillable = ['name', 'short_name', 'type_id', 'group_id', 'address', 'fax', 'telephone', 'email', 'status', 'logo'];

    public function getCompanyType(){
       return $this->belongsTo(CompanyType::class, 'type_id','id');
    }

    public function getGroup(){
        return $this->belongsTo(Group::class, 'group_id','id');
    }

}
