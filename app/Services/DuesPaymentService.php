<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Enum\RoleEnum;
use App\Repositories\DuesPaymentRepository;

class DuesPaymentService
{
    function __construct(
        protected DuesPaymentRepository $duesPaymentRepo,
    ) { }

    public function list(ListFilterDto $filter)
    {
        // jika user login adalah warga, data  yang ditampilkan hanya yang sesuai dengan user tersebut
        // jika user login adalah admin atau bendahara, tampilkan semua data
        if (auth_role() === RoleEnum::Warga) {
            $filter->search->authUserId = auth()->id();
        };
        return $this->duesPaymentRepo->list($filter);
    }
}