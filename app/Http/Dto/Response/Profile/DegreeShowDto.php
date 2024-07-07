<?php

namespace App\Http\Dto\Response\Profile;

use App\Http\Dto\Response\AbstractDto;
use App\Models\EducationalDegree;

class DegreeShowDto extends AbstractDto
{

    public function __construct($degree)
    {
        parent::__construct(EducationalDegree::class, $degree);
    }

    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('degree_id', $this->model->degree)
            ->setProperty('description', $this->model->description)
            ->setProperty('institution_count', $this->model->getInstitutionCount());
    }
}
