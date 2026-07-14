<?php

namespace App\Models\Setting;

use App\Traits\Userstamps;

use Illuminate\Database\Eloquent\Model;

class ProfileFieldConfig extends Model
{
    use Userstamps;
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
