<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GazetteLocation extends Model
{
    protected $table = 'gazette_locations';
    protected $fillable = ['name', 'status'];
}
