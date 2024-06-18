<?php

namespace App\Repositories;

use App\Interfaces\Repository\SecurityTokenRepositoryInterface;
use App\Models\SecurityToken;

class SecurityTokenRepository extends BaseRepository implements SecurityTokenRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(SecurityToken::class);
    }
}
