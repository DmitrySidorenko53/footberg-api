<?php

namespace App\Repositories;

use App\Interfaces\Repository\RoleUserRepositoryInterface;
use App\Models\RoleUser;

class RoleUserRepository extends BaseRepository implements RoleUserRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(RoleUser::class);
    }
}
