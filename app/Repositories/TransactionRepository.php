<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Repositories\Repository;

class TransactionRepository extends Repository
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }
}
