<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class DuesPaymentDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $resident_id,
        public ?string $dues_month_id,
        public ?string $parent_id,
        public ?float $base_amount,
        public ?int $unique_code,
        public ?float $final_amount,
        public ?bool $is_paid,
        public ?bool $is_merge,
    ) {
    }
}
