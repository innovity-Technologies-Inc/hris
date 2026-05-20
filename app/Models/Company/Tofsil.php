<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Tofsil extends Model
{
    protected $table = 'tofsils';
    protected $fillable = ['name', 'description', 'status'];
}

