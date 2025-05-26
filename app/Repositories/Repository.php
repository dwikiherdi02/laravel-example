<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(string $id)
    {
        $item = $this->model->find($id);
        if ($item) {
            return $item;
        }
        return null;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}