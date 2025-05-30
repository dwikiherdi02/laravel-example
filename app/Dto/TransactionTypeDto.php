<?php

namespace App\Dto;

use App\Enum\RoleEnum;
use Spatie\LaravelData\Data;

class TransactionTypeDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $name_lang_key,
        public ?string $code,
    ) {
    }
}
