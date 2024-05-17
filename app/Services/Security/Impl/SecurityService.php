<?php

namespace App\Services\Security\Impl;

use App\Http\Dto\DtoInterface;
use App\Services\Security\SecurityServiceInterface;

class SecurityService implements SecurityServiceInterface
{

    public function register(DtoInterface $dto)
    {
        return 'register method';
    }

    public function login(DtoInterface $dto)
    {
        return 'login method';// TODO: Implement login() method.
    }
}
