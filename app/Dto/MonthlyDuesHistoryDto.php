<?php

namespace App\Dto;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class MonthlyDuesHistoryDto extends Data
{
    public function __construct(
        public ?array $dues_payment_ids = null,
    ) {
    }
}
