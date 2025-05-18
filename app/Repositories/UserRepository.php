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

    function findByUsername(string $username): ?UserDto
    {
        $user = $this->model->where('username', $username)->first();
        if ($user) {
            return UserDto::from($user);
        }
        return null;
    }

    function create(array $data): UserDto
    {
        return UserDto::from($this->model->create($data));
    }
}

