<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class ContributionDto extends Data
{
    public function __construct(
        public ?string $id = null,
        public string $name,
        public float $amount = 0,
    ) {
    }
}
