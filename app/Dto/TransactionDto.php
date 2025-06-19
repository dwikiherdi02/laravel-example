<?php

namespace App\Dto;

use App\Enum\TransactionMethodEnum;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class TransactionDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $parent_id,
        public ?TransactionMethodEnum $transaction_method_id,
        public ?TransactionTypeEnum $transaction_type_id,
        public ?TransactionStatusEnum $transaction_status_id,
        public ?string $name,
        public ?string $dues_payment_id,
        public ?string $email_id,
        public ?string $account_name,
        // public ?float $amount,
        public ?float $base_amount,
        public ?int $point,
        public ?float $final_amount,
        public ?float $system_balance = 0,
        public ?string $date,

        // public ?CarbonImmutable $created_at = null,
        // public ?CarbonImmutable $updated_at = null,
    ) {
        // $this->created_at ??= CarbonImmutable::now()->format('Y-m-d H:i:s');
        // $this->updated_at ??= CarbonImmutable::now()->format('Y-m-d H:i:s');
    }
}
