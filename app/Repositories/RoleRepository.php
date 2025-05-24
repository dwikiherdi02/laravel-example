<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository
{
    function __construct(
        protected Role $model
    ) {
    }

    public function listOptions()
    {
        return $this->model
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
    }
}

