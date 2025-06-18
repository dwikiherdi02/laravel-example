<?php

namespace App\Dto\Lib\Imap;

use Spatie\LaravelData\Data;

class FilterDto extends Data
{
    public function __construct(
        public ?string $folder = "INBOX",
        public ?string $from = null,
        public ?string $subject = null,
        public ?bool $unseen = false,
        public ?bool $onToday = null,
        public ?bool $isOrderDesc = false,
        public ?int $limit = null,
        public ?bool $setFlagToSeen = false,
        // public ?bool $onlyOne = false,
    ) {
    }
}
