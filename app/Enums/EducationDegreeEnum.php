<?php

namespace App\Enums;

enum EducationDegreeEnum: string
{
    case HIGHER = 'Higher Education';
    case SECONDARY = 'Secondary Special Education';

    public static function keys(bool $key = true): array
    {
        $cases = self::cases();
        $keys = [];
        foreach ($cases as $case) {
            $keys[] = $key ? strtolower($case->name) : $case->value;
        }
        return $keys;
    }

}
