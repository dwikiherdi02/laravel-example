<?php

namespace App\Dto;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class DuesPaymentDetailDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $dues_payment_id,
        public ?string $contribution_id,
        public ?float $amount,

        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
        $now = CarbonImmutable::now();
        $this->created_at ??= $now->toDateTimeString();
        $this->updated_at ??= $now->toDateTimeString();
    }
}
