<?php

namespace App\Interfaces\Service;

use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;

interface ProfileServiceInterface
{
    public function fillDetails(DtoInterface $dto, $user): AbstractDto;

    public function getDetailsByUserId($userId, $isMy): AbstractDto;

    public function changeLanguage($user, $wantedLanguage): void;
}
