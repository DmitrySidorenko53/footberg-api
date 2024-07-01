<?php

namespace App\Repositories;

use App\Interfaces\Repository\EducationalDegreeRepositoryInterface;
use App\Models\EducationalDegree;

class EducationalDegreeRepository extends BaseRepository implements EducationalDegreeRepositoryInterface
{

    public function __construct()
    {
        parent::__construct(EducationalDegree::class);
    }
}
