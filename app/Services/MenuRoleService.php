<?php

namespace App\Services;

use App\Repositories\MenuRoleRepository;
use App\Repositories\MenuShortcutRepository;

class MenuRoleService
{
    function __construct(
        protected MenuRoleRepository $menuRoleRepo
    ) { }

    public function hasAccess(string $roleId, string $routeName): bool
    {
        if (!$this->menuRoleRepo->findByRoleIdAndRouteName($roleId, $routeName)) {
            return false;
        }
        return true;
    }
}