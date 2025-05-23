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
        public ?string $password = null,
        public bool $is_initial_login = true,
        public ?RoleDto $role = null,
        public ?ResidentDto $resident = null,
    ) {
    }
}
