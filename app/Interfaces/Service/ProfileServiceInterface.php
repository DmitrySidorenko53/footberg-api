<?php

namespace App\Interfaces\Service;

use App\Interfaces\DtoInterface;

interface ProfileServiceInterface
{
    public function fillDetails(DtoInterface $dto, $user);

    public function getDetailsByUserId($authId, $searchId);
}
