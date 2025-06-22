<?php

namespace App\Dto;

use App\Enum\TransactionTypeEnum;
use Spatie\LaravelData\Data;

class SystemBalanceDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?float $total_balance = 0,
        public ?int $total_point = 0,
        public ?float $final_balance = 0,
    ) {
    }
}
