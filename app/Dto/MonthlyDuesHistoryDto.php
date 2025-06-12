<?php

namespace App\Dto;

use App\Enum\IsMergeEnum;
use Spatie\LaravelData\Data;

class MonthlyDuesHistoryDto extends Data
{
    public function __construct(
        public ?array $dues_payment_ids = null,
        public ?IsMergeEnum $is_merge = null,
    ) {
    }
}
