<?php

namespace App\Dto;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class DuesPaymentDetailDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $dues_payment_id,
        public ?string $contribution_id,
        public ?float $amount,

        public ?CarbonImmutable $created_at = null,
        public ?CarbonImmutable $updated_at = null,
    ) {
        $this->created_at ??= CarbonImmutable::now();
        $this->updated_at ??= CarbonImmutable::now();
    }
}
