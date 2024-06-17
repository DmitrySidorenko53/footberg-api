<?php

namespace App\Repositories\Impl;

use App\Models\ConfirmationCode;
use App\Repositories\BaseRepository;
use App\Repositories\ConfirmationCodeRepositoryInterface;

class ConfirmationCodeRepository extends BaseRepository implements ConfirmationCodeRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(ConfirmationCode::class);
    }
}
