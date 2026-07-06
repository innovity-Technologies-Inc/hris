<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class ProfileFieldConfig extends Model
{
    protected $table = 'profile_field_configs';

    protected $fillable = [
        'section',
        'field_name',
        'label',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];
}
