<?php

namespace App\Repositories;

use App\Interfaces\Repository\AccountDetailsRepositoryInterface;
use App\Models\AccountDetails;

class AccountDetailsRepository extends BaseRepository implements AccountDetailsRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(AccountDetails::class);
    }
}
