<?php

namespace App\Repositories;

use App\Models\MenuGroup;

class MenuGroupRepository
{
    function __construct(
        protected MenuGroup $model,
    ) {

    }

    public function getSidebars(string $authRoleId = '')
    {
        return $this->model
            ->select('id', 'name', 'name_lang_key')
            ->with([
                'menus' => function ($query) use ($authRoleId) {
                    $query
                        ->select('id', 'menu_group_id', 'name', 'name_lang_key', 'icon', 'route_name', 'slug', 'sort', 'deleted_at')
                        ->whereHas('menuRole', function ($query) use ($authRoleId) {
                            $query->where('role_id', $authRoleId);
                        })
                        ->orderBy('sort');
                }
            ])
            ->whereHas('menus', function ($query) use ($authRoleId) {
                $query
                    ->whereNull('deleted_at')
                    ->whereHas('menuRole', function ($query) use ($authRoleId) {
                        $query->where('role_id', $authRoleId);
                    });
            })
            ->orderBy('sort')
            ->get();
    }
}

