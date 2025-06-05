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
            ->select('id', 'name', 'name_lang_key', 'icon', 'route_name', 'slug')
            ->whereHas('menuShortcut', function ($query) use ($authRoleId) {
                $query
                    ->where('role_id', $authRoleId);
            })
            ->orderBy(
                MenuShortcut::selectRaw('MIN(sort)')
                    ->whereColumn('menu_shortcuts.menu_id', 'menus.id')
                    ->where('role_id', $authRoleId)
            )->get();
    }

    public function getMenuBySlug(string $slug = '')
    {
        return $this->model
            ->select('id', 'name', 'name_lang_key', 'icon', 'slug')
            ->where('slug', $slug)
            ->first();
    }
}

