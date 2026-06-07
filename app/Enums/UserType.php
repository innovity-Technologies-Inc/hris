<?php

namespace App\Enums;

enum UserType: string
{
    case Group = 'Group';
    case Company = 'Company';
    case BusinessUnit = 'Business Unit';
    case Division = 'Division';
    case Department = 'Department';
    case Section = 'Section';
    case Employee = 'Employee';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
