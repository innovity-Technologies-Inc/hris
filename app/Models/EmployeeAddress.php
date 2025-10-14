<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'address_line_1',
        'village',
        'post_office',
        'thana',
        'district',
        'division',
        'zip_code',
        'country',
        'phone',
        'mobile',
        'reference_emp_id',
        'reference_name',
        'reference_designation',
        'reference_city',
        'reference_email',
    ];

    /**
     * Defines the inverse one-to-many relationship with the employee.
     */
    public function getEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
