<?php

namespace App\Repositories;

use App\Dto\ListDto\ListDto;
use App\Dto\ListDto\ListFilterDto;
use App\Dto\UserDto;
use App\Models\User;

class UserRepository
{
    function __construct(
        protected User $model,
    ) {
        //
    }

    function findById(string $id): ?User
    {
        $user = $this->model->find($id);
        if ($user) {
            return $user;
        }
        return null;
    }

    function findByUsername(string $username): ?User
    {
        $user = $this->model->where('username', $username)->first();
        if ($user) {
            return $user;
        }
        return null;
    }

    public function list(ListFilterDto $filter): ListDto
    {
        $query = $this->model->select([
            'id',
            'role_id',
            'resident_id',
            'name',
            'username',
            'password',
            'is_initial_login',
            'is_protected',
        ])
            ->with([
                'resident' => function ($q) {
                    $q->select('id', 'housing_block', 'unique_code');
                },
                'role' => function ($q) {
                    $q->select('id', 'name');
                }
            ]);

        if ($filter->search->general) {
            $gFilter = $filter->search->general;
            $query->where(function ($q) use ($gFilter) {
                $q->whereLike('name', '%' . $gFilter . '%')
                    ->orWhereLike('username', '%' . $gFilter . '%')
                    ->orWhereHas('resident', function ($q) use ($gFilter) {
                        $q->whereLike('name', '%' . $gFilter . '%')
                            ->orWhereLike('housing_block', '%' . $gFilter . '%')
                            ->orWhereLike('phone_number', '%' . $gFilter . '%')
                            ->orWhereLike('unique_code', '%' . $gFilter . '%')
                            ->orWhereLike('address', '%' . $gFilter . '%');
                    });
            });
        }

        if ($filter->search->role) {
            $query->where('role_id', $filter->search->role);
        }

        $query = $query->orderBy('created_at', 'desc');

        // Clone query untuk total count
        $total = (clone $query)->count();

        // Ambil data paginasi
        $users = $query
            ->limit($filter->perpage)
            ->offset(($filter->page - 1) * $filter->perpage)
            ->get();

        return ListDto::from([
            'data' => $users,
            'total' => $total,
        ]);
    }

    function create(array $data): User
    {
        return $this->model->create($data);
    }
}

