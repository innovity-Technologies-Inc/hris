<?php

namespace App\Models\Transport;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $fillable = [
        'vehicle_category',
        'model_number',
        'manufacture_year',
        'body_type',
        'fuel_type',
        'engine_capacity',
        'seating_capacity',
        'color',
        'mileage',
        'license_number',
        'license_document',
        'vehicle_image',
        'purchase_type',
        'purchase_date',
        'purchase_price',
        'purchase_document',
        'ownership_type',
        'third_party_name',
        'is_allocated',
        'allocation_purpose',
        'allocation_type',
        'status',
    ];

    protected $casts = [
        'is_allocated' => 'boolean',
    ];
}
