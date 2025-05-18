<?php

namespace App\Dto;

use App\Enum\RoleEnum;
use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public ?string $id = null,
        public RoleEnum $role_id,
        public ?string $resident_id = null,
        public string $name,
        public string $username,
        public string $password,
        public bool $is_initial_login = true,
    ) {
    }
}
