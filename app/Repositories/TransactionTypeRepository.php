<?php

namespace App\Repositories;

use App\Models\TransactionType;

// class TransactionTypeRepository extends Repository
class TransactionTypeRepository
{
    function __construct(
        protected TransactionType $model,
    ) {
        //
    }

    public function listOptions()
    {
        return $this->model->select([
            'id',
            'name',
            'name_lang_key',
            'code',
        ])->get();
    }
}
