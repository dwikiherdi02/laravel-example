<?php

namespace App\Dto\Lib\Imap;

use Spatie\LaravelData\Data;

class CollectionDto extends Data
{
    public function __construct(
        public ?string $body = null,
        public ?string $text = null,
        public ?string $date = null,
    ) {
    }
}
