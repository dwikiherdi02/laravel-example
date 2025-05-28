<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class ImapDto extends Data
{
    public function __construct(
        public ?string $host = null,
        public ?int $port = 0,
        public ?string $protocol = null,
        public ?string $encryption = null,
        public ?bool $validate_cert = false,
        public ?string $username = null,
        public ?string $password = null,
        public ?string $authentication = null,
    ) {
    }
}
