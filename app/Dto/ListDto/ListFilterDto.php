<?php

namespace App\Dto\ListDto;

use Spatie\LaravelData\Data;


class ListFilterSearchDto extends Data
{
    public function __construct(
        public ?string $general,
        public ?string $role,
        public ?string $transactionDate,
        public ?string $transactionType,
        public ?string $transactionMethod,
        public ?string $transactionStatus,
        public ?int $year,
        public ?int $month,
        public ?string $authUserId,
        public ?string $authUserRoleId,
        public ?bool $isPaid,
    ) {
    }
}

class ListFilterDto extends Data
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(
        public int $perpage,
        public int $page,
        public ListFilterSearchDto $search,

    ) {
    }

    /**
     * Factory method untuk membuat TableDto dari array state/table
     */
    public static function fromState(object $filter): self
    {
        return new self(
            $filter->perpage,
            $filter->npage->current,
            ListFilterSearchDto::from($filter->search),
        );
    }
}

