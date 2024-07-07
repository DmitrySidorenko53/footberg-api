<?php

namespace App\Http\Dto\Response\Profile;

use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\ApiCollection;
use App\Models\User;

class ProfileShowDto extends AbstractDto
{

    public function __construct($user)
    {
        parent::__construct(User::class, $user);
    }

    //todo unset model property
    /**
     * @throws InvalidIncomeTypeException
     */
    public function build($data = []): AbstractDto
    {
        $isMy = $data && array_key_exists('is_my', $data) && $data['is_my'];
        $dto = $this
            ->setProperty('userId', $this->model->user_id)
            ->setProperty('email', $this->model->email)
            ->setProperty('surname', $this->model->details->surname)
            ->setProperty('name', $this->model->details->name)
            ->setProperty('patronymic', $this->model->details->patronymic)
            ->setDateTime('birthdate', $this->model->details->birth_date, 'Y-m-d')
            ->setProperty('workplace', $this->model->details->work_place)
            ->setProperty('position', $this->model->details->position)
            ->setCollection('educations',
                new ApiCollection($this->model->educations, EducationShowDto::class)
            );
        if ($isMy) {
            $this
                ->setCollection('roles',
                    new ApiCollection($this->model->roles, RoleShowDto::class)
                );
        } else {
            $this
                ->setDateTime('lastLoginAt', $this->model->last_login_at)
                ->setDateTime('registerAt', $this->model->register_at);
        }
        return $dto;
    }
}
