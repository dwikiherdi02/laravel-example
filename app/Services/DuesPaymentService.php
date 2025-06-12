<?php

namespace App\Services;

use App\Dto\DuesPaymentDto;
use App\Dto\ListDto\ListFilterDto;
use App\Enum\RoleEnum;
use App\Repositories\DuesPaymentRepository;

class DuesPaymentService
{
    function __construct(
        protected DuesPaymentRepository $duesPaymentRepo,
    ) {
    }

    public function list(ListFilterDto $filter)
    {
        // jika user login adalah warga, data  yang ditampilkan hanya yang sesuai dengan user tersebut
        // jika user login adalah admin atau bendahara, tampilkan semua data
        if (auth_role() === RoleEnum::Warga) {
            $filter->search->authUserId = auth()->id();
        }
        ;
        return $this->duesPaymentRepo->list($filter);
    }

    public function findById(string $id)
    {
        $item = $this->duesPaymentRepo->findById($id);

        if ($item) {
            return $item;
        }

        return null;
    }

    public function listMergedByYearAndMonth(int $year, int $month)
    {
        return $this->duesPaymentRepo->listMergedByYearAndMonth($year, $month);
    }

    public function createHouseBillMerge(DuesPaymentDto $data)
    {
        dd($data);
    }
}
