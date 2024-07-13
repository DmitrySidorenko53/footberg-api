<?php

namespace App\Enums\Filters;

enum WhereClausesEnum: string
{
    case WHERE = 'where';
    case WHERE_DATE = 'whereDate';
    case WHERE_MONTH = 'whereMonth';
    case WHERE_YEAR = 'whereYear';
    case WHERE_DAY = 'whereDay';
    case WHERE_TIME = 'whereTime';
    case WHERE_COLUMN = 'whereColumn';
    case WHERE_IN = 'whereIn';
    case WHERE_NOT_IN = 'whereNotIn';
    case WHERE_NULL = 'whereNull';
    case WHERE_NOT_NULL = 'whereNotNull';
    case WHERE_BETWEEN = 'whereBetween';
    case WHERE_NOT_BETWEEN = 'whereNotBetween';
    case WHERE_BETWEEN_COLUMNS = 'whereBetweenColumns';
    case WHERE_NOT_BETWEEN_COLUMNS = 'whereNotBetweenColumns';
    case WHERE_FULL_TEXT = 'whereFullText';

    public static function keys($cases, $asString = true): array|string
    {
        $keys = [];
        foreach ($cases as $case) {
            $keys[] = $case->value;
        }
        return $asString ? implode('|', $keys) : $keys;
    }

    public static function requireArrayAsValueCases(): array
    {
        return [
            self::WHERE_IN,
            self::WHERE_NOT_IN,
        ];
    }

    public static function requireTwoElementsArrayAsValueCases(): array
    {
        return [
            self::WHERE_BETWEEN,
            self::WHERE_NOT_BETWEEN,
            self::WHERE_BETWEEN_COLUMNS,
            self::WHERE_NOT_BETWEEN_COLUMNS,
        ];
    }

    public static function requireOnlyFieldCases(): array
    {
        return [
            self::WHERE_NULL,
            self::WHERE_NOT_NULL,
        ];
    }

    public static function defaultCases(): array
    {
        return [
            self::WHERE,
            self::WHERE_DAY,
            self::WHERE_TIME,
            self::WHERE_MONTH,
            self::WHERE_YEAR,
            self::WHERE_DATE,
            self::WHERE_COLUMN,
        ];
    }
}
