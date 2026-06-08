<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Traits\Userstamps;
use App\Traits\Auditable;

class IDCardDesign extends Model
{
    use Userstamps, Auditable;
    protected $table = 'id_card_designs';

    protected $fillable = [
        'theme_name',
        'file_path',
        'status',
        'description',
        'preview_front_card',
        'preview_back_card'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all employee IDs using this design
     */
    public function employeeIds(): HasMany
    {
        return $this->hasMany(EmployeeId::class, 'id_card_design_id', 'id');
    }
}

