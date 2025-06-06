<?php

namespace App\Dto;

use App\Enum\TransactionTypeEnum;
use Spatie\LaravelData\Data;

class TextTemplateDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?TransactionTypeEnum $transaction_type_id,
        public ?string $email,
        public ?string $email_subject,
        public ?string $template,
    ) {
    }
}
