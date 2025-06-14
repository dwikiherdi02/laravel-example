<?php

namespace App\Repositories;

use App\Models\ResidentPoint;

class ResidentPointRepository extends Repository
{
    /**
     * ResidentPointRepository constructor.
     */
    public function __construct(ResidentPoint $model)
    {
        parent::__construct($model);
    }

    public function findByResidentId(string $residentId): ?ResidentPoint
    {
        return $this->model->where('resident_id', $residentId)->first();
    }
}
