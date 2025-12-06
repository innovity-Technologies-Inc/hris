<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeductionPlan extends Model
{
    use HasFactory;

    protected $table = 'deduction_plans';

    protected $fillable = [
        'late_deduction',
        'early_out_deduction',
        'excessive_late_deduction',
        'status',
    ];

    protected $casts = [
        'late_deduction' => 'decimal:2',
        'early_out_deduction' => 'decimal:2',
        'excessive_late_deduction' => 'decimal:2',
    ];

    /**
     * Validation rules for the DeductionPlan model.
     *
     * @return array
     */
    public static function validationRules(): array
    {
        return [
            'late_deduction' => 'required|numeric|min:0',
            'early_out_deduction' => 'required|numeric|min:0',
            'excessive_late_deduction' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }
}
