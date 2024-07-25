<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\Security\CodeDto;
use App\Interfaces\DtoInterface;

interface SmsServiceInterface
{
    public function sendSmsCode($user, string $newPhoneNumber = null): CodeDto;
    public function addPhoneNumberFor2Fa(DtoInterface $dto, $user): CodeDto;
}
