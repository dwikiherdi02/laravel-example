<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class TextTemplateDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $name,
        public ?string $transaction_type_id,
        public ?string $email,
        public ?string $email_subject,
        public ?string $template,
    ) {
    }
}
