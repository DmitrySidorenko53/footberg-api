<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;

interface ConfirmationCodeServiceInterface
{
    public function refreshCode($user, $scope): AbstractDto;
    public function createConfirmationCode($user, $scope): AbstractDto;
    public function tryConfirmCode($code, $dto);
}
