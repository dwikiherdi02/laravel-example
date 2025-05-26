<?php

namespace App\Repositories;

use App\Dto\ListDto\ListDto;
use App\Dto\ListDto\ListFilterDto;
use App\Models\Contribution;

class ContributionRepository extends Repository
{
    function __construct(
        // protected Contribution $model,
        Contribution $model,
    ) {
        parent::__construct($model);
    }

    public function list(ListFilterDto $filter): ListDto
    {
        $query = $this->model->select([
            'id',
            'name',
            'amount',
        ]);

        if ($filter->search->general) {
            $gFilter = $filter->search->general;
            $query->where(function ($q) use ($gFilter) {
                $q->whereLike('name', '%' . $gFilter . '%')
                    ->orWhereLike('amount', '%' . $gFilter . '%');
            });
        }

        $query = $query->orderBy('created_at', 'desc');

        // Clone query untuk total count
        $total = (clone $query)->count();

        // Ambil data paginasi
        $residents = $query
            ->limit($filter->perpage)
            ->offset(($filter->page - 1) * $filter->perpage)
            ->get();

        return ListDto::from([
            'data' => $residents,
            'total' => $total,
        ]);
    }
}
