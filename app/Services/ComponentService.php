<?php

namespace App\Services;

use App\Repositories\MenuGroupRepository;
use App\Repositories\MenuRepository;

class ComponentService
{

    function __construct(
        protected MenuGroupRepository $menuGroupRepo,
        protected MenuRepository $menuRepo,
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
}

