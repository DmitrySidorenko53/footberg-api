<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Interfaces\Repository\RoleUserRepositoryInterface;
use App\Interfaces\Service\RoleServiceInterface;
use InvalidArgumentException;

class RoleService implements RoleServiceInterface
{
    private RoleUserRepositoryInterface $roleUserRepository;

    public function __construct(RoleUserRepositoryInterface $roleUserRepository)
    {
        $this->roleUserRepository = $roleUserRepository;
    }

    public function refreshUsersRoles($user, array $roleIds): void
    {
        if (!$roleIds) {
            return;
        }

        $this->cleanUserRoles($user);

        $this->addRolesForUser($user, $roleIds, true);
    }

    public function addRolesForUser($user, array $roleIds, $forRefresh = false): void
    {
        if (!$forRefresh) {
            $this->cleanDefaultRole($user);

            $currentRolesCount = $this->roleUserRepository->countBy('user_id', $user->user_id);

            if ($currentRolesCount + sizeof($roleIds) > 2) {
                throw new InvalidArgumentException(__('exceptions.too_many_roles'));
            }
        }

        $rolesToAdd = [];
        foreach ($roleIds as $roleId) {
            $rolesToAdd[] = [
                'user_id' => $user->user_id,
                'role_id' => (int)$roleId
            ];
        }

        $this->roleUserRepository->insertIgnore($rolesToAdd);
    }

    /**
     * @param $user
     * @return void
     */
    private function cleanDefaultRole($user): void
    {
        $this->roleUserRepository->deleteWithFilters(
            [
                'role_id' => RoleEnum::VISITOR->value,
                'user_id' => $user->user_id
            ]
        );
    }

    private function cleanUserRoles($user): void
    {
        $this->roleUserRepository->deleteBy('user_id', $user->user_id);
    }
}
