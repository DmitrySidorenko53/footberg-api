<?php

namespace App\Services;

use App\Models\User;

interface ConfirmationCodeServiceInterface
{
    public function sendEmail($confirmationCode, $email);
    public function refreshCode($user);
    public function createConfirmationCode($user);
    public function confirmCode($code);
    public function isValid($code, string $codeToCompare);
}
