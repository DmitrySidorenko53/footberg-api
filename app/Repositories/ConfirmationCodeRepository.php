<?php

namespace App\Repositories;

use App\Interfaces\Repository\ConfirmationCodeRepositoryInterface;
use App\Models\ConfirmationCode;

class ConfirmationCodeRepository extends BaseRepository implements ConfirmationCodeRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(ConfirmationCode::class);
    }
}
