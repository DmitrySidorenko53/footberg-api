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
    public function build($data = []): AbstractDto
    {
        $isMy = $data && array_key_exists('is_my', $data) && $data['is_my'];
        $dto = $this
            ->setProperty('user_id', $this->model->user_id)
            ->setProperty('email', $this->model->email)
            ->setDto('details', AccountDetailsDto::class, $this->model->details)
            ->setCollection('educations', EducationShowDto::class, $this->model->educations);
        if ($isMy) {
            $this
                ->setCollection('roles', RoleShowDto::class, $this->model->roles);
        } else {
            $this
                ->setDateTime('last_login_at', $this->model->last_login_at)
                ->setDateTime('register_at', $this->model->register_at);
        }
        return $dto;
    }
}
