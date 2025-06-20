<?php

namespace App\Dto;

use Spatie\LaravelData\Data;

class EmailDto extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $text_template_id,
        public ?string $body_text,
        public ?string $body_html,
        public ?bool $is_read,

        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {
    }
}
