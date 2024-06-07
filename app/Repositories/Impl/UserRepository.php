<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected function setModel(): void
    {
        $this->model = User::class;
    }
}
