<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;

interface SecurityPasswordServiceInterface
{
    public function forgotPassword(DtoInterface $dto): AbstractDto;

    public function resetPassword(DtoInterface $dto);

    public function recoveryPassword(DtoInterface $dto): AbstractDto;
}
