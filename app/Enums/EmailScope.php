<?php

namespace App\Enums;

enum EmailScope: string
{
    case CONFIRMATION = 'confirmation-mail';
    case RESET = 'reset-password-mail';
}
