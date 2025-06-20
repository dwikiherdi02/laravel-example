<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class TransactionInfoDto extends Data
{
    public function __construct(
        public ?string $sender_name,
        public ?string $sender_account,
        public ?string $recipient_name,
        public ?string $recipient_account,
        public ?float $amount = 0,
        public ?string $datetime,
        public ?string $date,
        public ?string $time,
    ) {
    }
}
