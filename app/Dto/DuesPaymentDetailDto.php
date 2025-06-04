<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class DuesPaymentDetailDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $dues_payment_id,
        public ?string $contribution_id,
        public ?float $amount,
    ) {
    }
}
