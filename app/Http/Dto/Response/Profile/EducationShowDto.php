<?php

namespace App\Http\Dto\Response\Profile;


use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Response\AbstractDto;
use App\Models\EducationalInstitution;

/**
 * Class EducationShowDto
 * @property int $education_id
 * @property int $users_count
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property DegreeShowDto $degree
 */
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
            ->setDateTime('start_date', $this->model->pivot->start_date, 'Y-m-d')
            ->setDateTime('end_date', $this->model->pivot->end_date, 'Y-m-d')
            ->setProperty('users_count', $this->model->usersCount());
    }
}
