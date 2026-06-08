<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class Menu extends Model
{
    use Userstamps, Auditable;
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'icon',
        'route',
        'order',
        'status',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function submenus()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }
}

