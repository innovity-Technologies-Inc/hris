<?php

namespace App\Models\Plan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TAPlan extends Model
{
    use HasFactory;

    protected $table = 'ta_plans';

    protected $fillable = [
        'name',
        'short_name',
        'remuneration',
        'status',
    ];
}

