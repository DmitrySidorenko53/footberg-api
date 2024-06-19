<?php

namespace App\Repositories;

use App\Interfaces\Repository\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(User::class);
    }
}
