<?php

namespace App\Repositories;

use App\Models\Imap;

class ImapRepository
{
    function __construct(
        protected Imap $model
        
    ) { }

    public function get(): ?Imap
    {
        return $this->model->first();
    }

    public function create(array $data): Imap
    {
        return $this->model->create($data);
    }
}