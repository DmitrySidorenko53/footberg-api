<?php

namespace App\Repositories;

use App\Interfaces\Repository\LocaleRepositoryInterface;
use App\Models\SupportedLocale;

class LocaleRepository extends BaseRepository implements LocaleRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(SupportedLocale::class);
    }
}
