<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\Security\CodeDto;
use App\Interfaces\DtoInterface;
use Illuminate\Contracts\Auth\Authenticatable;

interface SecurityPasswordServiceInterface
{
    public function forgotPassword(DtoInterface $dto): CodeDto;

    public function resetPassword(DtoInterface $dto);

    public function recoveryPassword(DtoInterface $dto): AbstractDto;

    public function changePassword(DtoInterface $dto, Authenticatable $user): AbstractDto;
}
