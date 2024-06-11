<?php

namespace App\Repositories\Impl;

use App\Models\ConfirmationCode;
use App\Repositories\BaseRepository;
use App\Repositories\ConfirmationCodeRepositoryInterface;

class ConfirmationCodeRepository extends BaseRepository implements ConfirmationCodeRepositoryInterface
{

    protected function setModel(): void
    {
        $this->model = ConfirmationCode::class;
    }
}
