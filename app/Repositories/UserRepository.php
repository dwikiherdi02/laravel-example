<?php

namespace App\Repositories;

use App\Dto\UserDto;
use App\Models\User;

class UserRepository
{
    function __construct(
        protected User $model,
    ) {
        //
    }

    function findByUsername(string $username): ?User
    {
        $user = $this->model->where('username', $username)->first();
        if ($user) {
            return $user;
        }
        return null;
    }

    function create(array $data): User
    {
        return $this->model->create($data);
    }
}

