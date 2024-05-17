<?php

namespace App\Services\Security;

use App\Http\Dto\DtoInterface;

interface SecurityServiceInterface
{
    public function register(DtoInterface $dto);
    public function login(DtoInterface $dto);
}
