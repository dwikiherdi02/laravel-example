<?php

namespace App\Repositories;

use App\Enum\TransactionTypeEnum;
use App\Models\Email;

class EmailRepository extends Repository
{
    public function __construct(Email $model)
    {
        parent::__construct($model);
    }

    public function getUnseenEmailByTransactionType(TransactionTypeEnum $type)
    {
        return $this->model
            ->select(['*'])
            ->where('is_read', false)
            ->whereHas('template', function ($query) use ($type) {
                $query->where('transaction_type_id', $type->value);
            })
            ->get();
    }
}
