<?php

namespace App\Http\Dto\Response\Profile;

use App\Http\Dto\Response\AbstractDto;
use App\Models\AccountDetails;

/**
 * Class AccountDetailsDto
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $birthdate
 * @property string $workplace
 * @property string $position
 */
class AccountDetailsDto extends AbstractDto
{

    public function __construct($details)
    {
        parent::__construct(AccountDetails::class, $details);
    }

    protected function build($additionalData = []): AbstractDto
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
