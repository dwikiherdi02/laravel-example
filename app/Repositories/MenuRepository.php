<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Models\MenuShortcut;

class MenuRepository
{
    function __construct(
        protected Menu $model
    ) {
    }

    public function getMenuShortcuts(string $authRoleId = '')
    {
        return $this->model
            ->select('id', 'name', 'name_lang_key', 'icon', 'slug')
            ->whereHas('menuShortcut', function ($query) use ($authRoleId) {
                $query
                    ->where('role_id', $authRoleId);
            })
            ->orderBy(
                MenuShortcut::select('sort')
                    ->whereColumn('menu_shortcuts.menu_id', 'menus.id')
            )
            ->get();
    }
}

