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

    public function weight(): int
    {
        return match($this) {
            self::Group => 0,
            self::Company => 1,
            self::BusinessUnit => 2,
            self::Division => 3,
            self::Department => 4,
            self::Section => 5,
            self::Employee => 6,
        };
    }

    public static function getWeight(string $value): int
    {
        $normalized = str_replace('_', '-', strtolower($value));
        $case = self::tryFrom($normalized);
        return $case ? $case->weight() : 99;
    }
}
