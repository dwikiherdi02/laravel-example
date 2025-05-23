<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class RoleDto extends Data
{
    public function __construct(
        public ?string $id = null,
        public string $name
    ) {
    }
}
