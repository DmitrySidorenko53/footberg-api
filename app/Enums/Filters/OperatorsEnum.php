<?php

namespace App\Enums\Filters;

enum OperatorsEnum: string
{
    case EQUAL = '=';
    case GREATER = '>';
    case LESS = '<';
    case GREATER_OR_EQUAL = '>=';
    case LESS_OR_EQUAL = '<=';
    case NOT_EQUAL = '<>';
    case LIKE = 'like';
    case NOT_LIKE = 'not like';

    public static function likeOperators(): array
    {
        return [
            self::LIKE,
            self::NOT_LIKE,
        ];
    }

    public static function defaultOperators(): array
    {
        return [
            self::EQUAL,
            self::GREATER,
            self::LESS,
            self::GREATER_OR_EQUAL,
            self::LESS_OR_EQUAL,
            self::NOT_EQUAL
        ];
    }
}
