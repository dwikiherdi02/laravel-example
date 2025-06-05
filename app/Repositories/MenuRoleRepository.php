<?php

namespace App\Repositories;

use App\Models\MenuRole;


class MenuRoleRepository extends Repository
{
    function __construct(
        MenuRole $model,
    ) {
        parent::__construct($model);
    }

    public function findByRoleIdAndRouteName(string $roleId, string $routeName)
    {
        return $this->model
            ->where('role_id', $roleId)
            ->whereHas('menu', function ($query) use ($routeName) {
                $query->where('route_name', $routeName);
            })
            ->first();
    }
}