<?php

namespace App\Http\Dto\Response\Profile;

use App\Http\Dto\Response\AbstractDto;
use App\Models\AccountDetails;

class AccountDetailsDto extends AbstractDto
{

    public function __construct($details)
    {
        parent::__construct(AccountDetails::class, $details);
    }

    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('surname', $this->model->surname)
            ->setProperty('name', $this->model->name)
            ->setProperty('patronymic', $this->model->patronymic)
            ->setDateTime('birthdate', $this->model->birth_date, 'Y-m-d')
            ->setProperty('workplace', $this->model->work_place)
            ->setProperty('position', $this->model->position);
    }
}
