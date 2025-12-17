<?php

namespace App\Models;

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

    protected $casts = [
        'remuneration' => 'decimal:2',
    ];
}
