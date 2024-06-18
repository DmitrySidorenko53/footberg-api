<?php

namespace App\Interfaces\Service;

use App\Interfaces\DtoInterface;

interface SecurityServiceInterface
{
    public function register(DtoInterface $dto);
    public function login(DtoInterface $dto);
    public function refreshCode($dto);
    public function confirmAccount(DtoInterface $dto);
}
