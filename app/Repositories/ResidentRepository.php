<?php

namespace App\Repositories;

use App\Dto\ResidentDto;
use App\Models\Resident;

class ResidentRepository
{
    function __construct(
        protected Resident $model,
    ) {
    }

    public function create(array $data): ResidentDto
    {
        return ResidentDto::from($this->model->create($data));
    }
}
