<?php

namespace App\Services;

interface ConfirmationCodeServiceInterface
{
    public function refreshCode($user);
    public function createConfirmationCode($user);
    public function confirmCode($code);
    public function isValid($code, string $codeToCompare);
}
