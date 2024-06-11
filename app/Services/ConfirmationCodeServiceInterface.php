<?php

namespace App\Services;

interface ConfirmationCodeServiceInterface
{

    public function sendEmail($confirmationCode, $email);
    public function createConfirmationCode($user);
}
