<?php

namespace App\Services;

use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Service\TwoFactorServiceInterface;

class TwoFactorService implements TwoFactorServiceInterface
{

    public function enableTwoFactorAuthentication(DtoInterface $dto, $user): AbstractDto
    {
        // TODO: Implement enableTwoFactorAuthentication() method.
    }

    public function disableTwoFactorAuthentication($user): AbstractDto
    {
        // TODO: Implement disableTwoFactorAuthentication() method.
    }
}
