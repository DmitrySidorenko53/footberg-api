<?php

namespace App\Interfaces\Service;

interface ConfirmationCodeServiceInterface
{
    public function refreshCode($user);
    public function createConfirmationCode($user);
    public function confirmCode($code);
    public function isValid($code, string $codeToCompare);
}