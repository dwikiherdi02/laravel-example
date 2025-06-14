<?php

namespace App\Dto;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class TransactionDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $transaction_method_id,
        public ?string $transaction_type_id,
        public ?string $transaction_status_id,
        public ?string $dues_payment_id,
        public ?string $email_id,
        public ?string $account_name,
        public ?float $amount,
        public ?CarbonImmutable $date,

        // public ?CarbonImmutable $created_at = null,
        // public ?CarbonImmutable $updated_at = null,
    ) {
        // $this->created_at ??= CarbonImmutable::now()->format('Y-m-d H:i:s');
        // $this->updated_at ??= CarbonImmutable::now()->format('Y-m-d H:i:s');
    }
}
