<?php

namespace App\Http\Dto\Response\Profile;


use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Response\AbstractDto;
use App\Models\EducationalInstitution;

class EducationShowDto extends AbstractDto
{

    public function __construct($education)
    {
        parent::__construct(EducationalInstitution::class, $education);
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('education_id', $this->model->id)
            ->setProperty('title', $this->model->title)
            ->setProperty('degree',
                (new DegreeShowDto($this->model->degree))->build()
            )
            ->setDateTime('startDate', $this->model->pivot->start_date, 'Y-m-d')
            ->setDateTime('endDate', $this->model->pivot->end_date, 'Y-m-d')
            ->setProperty('usersCount', $this->model->usersCount());
    }
}
