<?php

namespace App\Http\Dto\Response\Profile;

use App\Http\Dto\Response\AbstractDto;
use App\Models\EducationalDegree;

/**
 * Class DegreeShowDto
 * @property string $degree_id
 * @property string $description
 * @property int $institution_count
 */
class DegreeShowDto extends AbstractDto
{

    public function __construct($degree)
    {
        parent::__construct(EducationalDegree::class, $degree);
    }

    protected function build($additionalData = []): AbstractDto
    {
        return $this
            ->setProperty('degree_id', $this->model->degree)
            ->setProperty('description', $this->model->description)
            ->setProperty('institution_count', $this->model->getInstitutionCount());
    }
}
