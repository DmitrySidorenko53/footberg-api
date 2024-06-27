<?php

namespace App\Enums;

enum EmailScopeEnum: string
{
    case CONFIRMATION = 'confirmation-mail';
    case RESET = 'reset-password-mail';
}
