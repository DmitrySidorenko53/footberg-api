<?php

namespace App\Interfaces\Service;

use App\Interfaces\DtoInterface;

interface SecurityPasswordServiceInterface
{
    public function forgotPassword(DtoInterface $dto);

    public function resetPassword(DtoInterface $dto);

    public function recoveryPassword(DtoInterface $dto);
}
