<?php

namespace App\Repositories;

use App\Interfaces\Repository\UserEducationRepositoryInterface;
use App\Models\UserEducation;

class UserEducationRepository extends BaseRepository implements UserEducationRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(UserEducation::class);
    }
}
