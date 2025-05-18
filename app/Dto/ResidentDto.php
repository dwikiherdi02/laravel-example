<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class ResidentDto extends Data
{
    public function __construct(
        public ?string $id = null,
        public string $name,
        public string $housing_block,
        public string $phone_number,
        public string $address,
        public string $unique_code,
    ) {
    }
}
