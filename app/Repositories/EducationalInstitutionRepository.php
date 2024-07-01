<?php

namespace App\Repositories;

use App\Interfaces\Repository\EducationalInstitutionRepositoryInterface;
use App\Models\EducationalInstitution;

class EducationalInstitutionRepository extends BaseRepository implements EducationalInstitutionRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(EducationalInstitution::class);
    }
}
