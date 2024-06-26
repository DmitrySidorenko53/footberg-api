<?php

namespace App\Interfaces\Service;

interface ConfirmationCodeServiceInterface
{
    public function refreshCode($user, $scope);
    public function createConfirmationCode($user, $scope);
    public function tryConfirmCode($code, $dto);
}
