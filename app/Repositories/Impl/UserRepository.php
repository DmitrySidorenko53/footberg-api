<?php

namespace App\Repositories\Impl;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(User::class);
    }
}
