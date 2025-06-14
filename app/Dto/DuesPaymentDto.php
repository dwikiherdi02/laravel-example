<?php

namespace App\Dto;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
        public ?int $is_merge,

        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
        $now = CarbonImmutable::now();
        $this->created_at ??= $now->toDateTimeString();
        $this->updated_at ??= $now->toDateTimeString();
    }
}
