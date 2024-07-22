<?php

namespace App\Enums;

enum CountryPhonePrefixEnum: string
{
    case BLR = '+375';
    case RU = '+79';
    case KZ = '+7';

    public static function getPrefix($country): string
    {
        return match (strtoupper($country)) {
            self::BLR->name => self::BLR->value,
            self::RU->name => self::RU->value,
            self::KZ->name => self::KZ->value,
        };
    }
}
