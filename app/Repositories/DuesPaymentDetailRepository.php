<?php

namespace App\Repositories;

use App\Models\DuesPaymentDetail;

class DuesPaymentDetailRepository extends Repository
{
    function __construct(
        DuesPaymentDetail $model
    ) {
        parent::__construct($model);
    }
}