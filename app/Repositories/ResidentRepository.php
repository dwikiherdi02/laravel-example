<?php

namespace App\Repositories;

use App\Dto\ListDto\ListDto;
use App\Dto\ListDto\ListFilterDto;
use App\Dto\ResidentDto;
use App\Models\Resident;

class ResidentRepository extends Repository
{
    function __construct(
        // protected Resident $model,
        Resident $model,
    ) {
        parent::__construct($model);
    }

    /* public function findById(string $id): ?Resident
    {
        $resident = $this->model->find($id);
        if ($resident) {
            return $resident;
        }
        return null;
    } */

    public function list(ListFilterDto $filter): ListDto
    {
        $query = $this->model->select([
            'id',
            'name',
            'housing_block',
            'phone_number',
            'address',
            'unique_code',
        ]);

        if ($filter->search->general) {
            $gFilter = $filter->search->general;
            $query->where(function ($q) use ($gFilter) {
                $q->whereLike('name', '%' . $gFilter . '%')
                    ->orWhereLike('housing_block', '%' . $gFilter . '%')
                    ->orWhereLike('phone_number', '%' . $gFilter . '%')
                    ->orWhereLike('unique_code', '%' . $gFilter . '%')
                    ->orWhereLike('address', '%' . $gFilter . '%');
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
            // 'data' => $residents->map(function ($item) {
            //     return ResidentDto::from($item);
            // }),
            'data' => $residents,
            'total' => $total,
        ]);
    }

    public function listUnsetUserOptions()
    {
        return $this->model
            ->where(function ($query) {
                $query->whereDoesntHave('user')
                    ->orWhereHas('user', function ($q) {
                        $q->whereNotNull('deleted_at');
                    });
            })
            ->get();
    }

    /* public function create(array $data): Resident
    {
        return $this->model->create($data);
    } */
}
