<?php

namespace App\Enums;

enum UserType: string
{
    case Group = 'group';
    case Company = 'company';
    case BusinessUnit = 'business-unit';
    case Division = 'division';
    case Department = 'department';
    case Section = 'section';
    case Employee = 'employee';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
