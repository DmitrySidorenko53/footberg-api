<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;

interface SmsServiceInterface
{
    public function sendCodeForTwoFactor(DtoInterface $dto, $user): AbstractDto;
}
