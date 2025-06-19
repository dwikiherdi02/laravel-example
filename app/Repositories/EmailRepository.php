<?php

namespace App\Repositories;

use App\Models\Email;

class EmailRepository extends Repository
{
    public function __construct(Email $model)
    {
        parent::__construct($model);
    }
}