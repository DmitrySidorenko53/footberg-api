<?php

namespace App\Interfaces\Service;

interface SecurityTokenServiceInterface
{
    public function generateToken($user);
}
