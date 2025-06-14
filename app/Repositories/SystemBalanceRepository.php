<?php

namespace App\Repositories;

use App\Models\SystemBalance;

class SystemBalanceRepository extends Repository
{
    public function __construct(SystemBalance $model)
    {
        parent::__construct($model);
    }

    public function getLockedBalance(): ?SystemBalance
    {
        return $this->model->lockForUpdate()->first();
    }
}
