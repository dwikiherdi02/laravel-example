<?php

namespace App\Repositories;

use App\Models\UserPoint;

class UserPointRepository extends Repository
{
    /**
     * UserPointRepository constructor.
     */
    public function __construct(UserPoint $model)
    {
        parent::__construct($model);
    }
}
