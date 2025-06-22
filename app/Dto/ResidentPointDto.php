<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class ResidentPointDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $resident_id,
        public ?int $total_point = 0,
    ) {
    }
}
