<?php

namespace App\Http\Dto\Response\Profile;

use App\Http\Dto\Response\AbstractDto;
use App\Models\Role;

/**
 * Class RoleShowDto
 *
 * @property int $role_id
 * @property int $users_count
 * @property string $description
 * @property string $shortcut
 */
class RoleShowDto extends AbstractDto
{

    public function __construct($role)
    {
        parent::__construct(Role::class, $role);
    }

    protected function build($additionalData = []): AbstractDto
    {
        return $this
            ->setProperty('role_id', $this->model->role_id)
            ->setProperty('shortcut', $this->model->shortcut)
            ->setProperty('description', $this->model->description)
            ->setProperty('users_count', $this->model->usersCount());
    }
}
