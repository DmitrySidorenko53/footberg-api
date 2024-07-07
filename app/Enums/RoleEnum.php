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

    public static function keys($cases, bool $id = false): array
    {
        $keys = [];
        foreach ($cases as $case) {
            $keys[] = $id ? $case->value : strtolower($case->name);
        }
        return $keys;
    }

    public static function visibleRoles(bool $asString = false): array|string
    {
        $roles = [
            self::BUYER,
            self::DEALER,
            self::INTERN,
            self::NURSE,
            self::SURGEON,
            self::VET_SURGEON,
            self::STUDENT
        ];

        if (!$asString) {
            return $roles;
        }

        $values = [];
        foreach ($roles as $role) {
            $values[] = $role->value;
        }

        return implode('|', $values);
    }
}