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
}
