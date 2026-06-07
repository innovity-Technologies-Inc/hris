<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'Super Admin';
    case HRManager = 'HR Manager';
    case DepartmentManager = 'Department Manager';
    case Employee = 'Employee';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
