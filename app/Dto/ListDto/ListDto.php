<?php

namespace App\Dto\ListDto;

use Spatie\LaravelData\Data;

class ListDto extends Data
{
    public function __construct(
        public array|object $data,
        public int $total,
    ) {
    }
}

