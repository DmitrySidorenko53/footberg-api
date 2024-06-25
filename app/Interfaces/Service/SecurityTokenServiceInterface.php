<?php

namespace App\Interfaces\Service;

use App\Interfaces\DtoInterface;

interface SecurityTokenServiceInterface
{
    public function generateToken($user);

    public function refresh(DtoInterface $dto);
}
