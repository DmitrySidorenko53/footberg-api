<?php

namespace App\Enums;

enum EducationDegreeEnum: string
{
    case HIGHER = 'Высшее образование';
    case SECONDARY = 'Средне-специальное образование';

    public static function keys(bool $key = true, $asString = false): array|string
    {
        $cases = self::cases();
        $keys = [];
        foreach ($cases as $case) {
            $keys[] = $key ? strtolower($case->name) : $case->value;
        }
        return $asString ? implode('|', $keys) : $keys ;
    }

}
