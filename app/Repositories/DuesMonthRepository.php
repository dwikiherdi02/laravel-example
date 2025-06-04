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
}