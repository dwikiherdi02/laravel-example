<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class DuesMonthDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?int $year = 0,
        public ?int $month = 0,
        public ?array $contribution_ids,
        public ?string $dues_date, // combination of year and month
    ) {
    }
}
