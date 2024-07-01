<?php

namespace App\Interfaces\Service;

interface RoleServiceInterface
{
    public function refreshUsersRoles($user, array $roleIds);
    public function addRolesForUser($user, array $roleIds, $forRefresh = false);
}
