<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Requests\Code\CodeDto;
use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;

interface SecurityServiceInterface
{
    public function register(DtoInterface $dto): AbstractDto;
    public function login(DtoInterface $dto): CodeDto|AbstractDto;
    public function refreshCode(DtoInterface $dto): AbstractDto;
    public function confirmAccount(DtoInterface $dto): AbstractDto;
}
