<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class Repository
{
    protected Model $model;

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

    public function getAll()
    {
        return $this->model->all();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function createMany(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateMany(array $conditions, array $data)
    {
        // return $this->model->where($conditions)->update($data);
        $query = $this->model->newQuery();
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }
        return $query->update($data);
    }
}
