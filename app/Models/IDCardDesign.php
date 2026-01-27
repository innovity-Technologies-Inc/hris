<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IDCardDesign extends Model
{
    protected $table = 'id_card_designs';

    protected $fillable = [
        'theme_name',
        'file_path',
        'status',
        'description',
        'preview_image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
