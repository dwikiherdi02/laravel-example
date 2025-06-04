<?php

namespace App\Repositories;

use App\Models\DuesPayment;

class DuesPaymentRepository extends Repository
{
    function __construct(
        DuesPayment $model
    ) {
        parent::__construct($model);
    }
}