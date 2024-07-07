<?php

namespace App\Http\Dto\Response\Profile;

use App\Http\Dto\Response\AbstractDto;
use App\Models\Role;

class RoleShowDto extends AbstractDto
{

    public function __construct($role)
    {
        parent::__construct(Role::class, $role);
    }

    public function build($data = []): AbstractDto
    {
        return $this
            ->setProperty('roleId', $this->model->role_id)
            ->setProperty('shortcut', $this->model->shortcut)
            ->setProperty('description', $this->model->description)
            ->setProperty('usersCount', $this->model->usersCount());
    }
}
