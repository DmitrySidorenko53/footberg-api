<?php

namespace App\Enums;

enum ConfirmationCodeScopeEnum: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
    case RESET = 'reset';
}
