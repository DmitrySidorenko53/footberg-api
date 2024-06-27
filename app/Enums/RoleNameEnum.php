<?php

namespace App\Enums;

enum RoleNameEnum: int
{
    case BUYER = 1;
    case DEALER = 2;
    case INTERN = 3;
    case NURSE = 4;
    case SURGEON = 5;
    case VET_SURGEON = 6;
    case STUDENT = 7;
    case VISITOR = 8;
}
