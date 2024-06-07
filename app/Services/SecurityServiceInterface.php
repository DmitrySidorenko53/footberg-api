<?php

namespace App\Services;

use App\Http\Dto\Requests\DtoInterface;

interface SecurityServiceInterface
{
    public function register(DtoInterface $dto);
    public function login(DtoInterface $dto);
}
