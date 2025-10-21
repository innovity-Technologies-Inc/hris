<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeNetwork extends Model
{
    use HasFactory;

    protected $table = 'employee_networks';

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'gender',
        'birth_date',
        'relationship',
        'personal_mobile',
        'personal_email',
        'is_dependant',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_dependant' => 'boolean',
    ];

    public function getEmployee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
