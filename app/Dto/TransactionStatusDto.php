<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class TransactionStatusDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $name_lang_key,
    ) {
    }
}
