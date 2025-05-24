<?php

namespace App\Services;

use App\Dto\RoleDto;
use App\Repositories\MenuGroupRepository;
use App\Repositories\MenuRepository;
use App\Repositories\RoleRepository;

class ComponentService
{

    function __construct(
        protected MenuGroupRepository $menuGroupRepo,
        protected MenuRepository $menuRepo,
        protected RoleRepository $roleRepo,
    ) {
        //
    }

    public function getSidebars(string $authRoleId = '')
    {
        return $this->menuGroupRepo->getSidebars($authRoleId);
    }

    public function getMenuShortcuts(string $authRoleId = '')
    {
        return $this->menuRepo->getMenuShortcuts($authRoleId);
    }

    public function getMenuBySlug(string $slug = '')
    {
        return $this->menuRepo->getMenuBySlug($slug);
    }

    public function getRoleOptions()
    {
        return $this->roleRepo->listOptions()
            ->map(function ($item) {
                return new RoleDto(
                    id: $item->id,
                    name: $item->name
                );
            });
    }
}

