<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;

interface TwoFactorServiceInterface
{
    public function enableTwoFactorAuthentication(DtoInterface $dto, $user): AbstractDto;
    public function disableTwoFactorAuthentication($user): AbstractDto;
}