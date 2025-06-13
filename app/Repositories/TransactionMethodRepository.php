<?php

namespace App\Repositories;

use App\Models\TransactionMethod;

// class TransactionMethodRepository extends Repository
class TransactionMethodRepository
{
    function __construct(
        protected TransactionMethod $model,
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
