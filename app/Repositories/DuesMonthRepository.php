<?php

namespace App\Repositories;

use App\Models\DuesMonth;

class DuesMonthRepository extends Repository
{
    function __construct(
        DuesMonth $model
    ) {
        parent::__construct($model);
    }

    public function findByYearAndMonth(int $year, int $month)
    {
        return $this->model->where('year', $year)->where('month', $month)->first();
    }
}