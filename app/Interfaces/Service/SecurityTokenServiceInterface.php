<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;

interface SecurityTokenServiceInterface
{
    public function generateToken($user): AbstractDto;

    public function refresh(DtoInterface $dto): AbstractDto;

    public function resetTokens($user);
}
