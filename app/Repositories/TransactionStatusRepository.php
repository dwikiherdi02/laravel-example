<?php

namespace App\Repositories;

use App\Models\TransactionStatus;

// class TransactionMethodRepository extends Repository
class TransactionStatusRepository
{
    function __construct(
        protected TransactionStatus $model,
    ) {
        //
    }

    public function listOptions()
    {
        return $this->model->select([
            'id',
            'name',
            'name_lang_key',
        ])->get();
    }
}
