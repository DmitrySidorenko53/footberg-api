<?php

namespace App\Enums;

enum RoleEnum: int
{
    case BUYER = 1;
    case DEALER = 2;
    case INTERN = 3;
    case NURSE = 4;
    case SURGEON = 5;
    case VET_SURGEON = 6;
    case STUDENT = 7;
    case VISITOR = 8;
    case ADMIN = 9;

    public static function visibleRoles(): array
    {
        return [
            self::BUYER,
            self::DEALER,
            self::INTERN,
            self::NURSE,
            self::SURGEON,
            self::VET_SURGEON,
            self::STUDENT
        ];
    }
}
