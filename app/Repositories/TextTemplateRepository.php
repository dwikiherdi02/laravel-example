<?php

namespace App\Repositories;

use App\Dto\ListDto\ListDto;
use App\Dto\ListDto\ListFilterDto;
use App\Models\TextTemplate;

class TextTemplateRepository extends Repository
{
    public function __construct(TextTemplate $model)
    {
        parent::__construct($model);
    }

    public function list(ListFilterDto $filter): ListDto
    {
        $query = $this->model->select([
            'id',
            'name',
            'transaction_type_id',
            'email',
            'email_subject',
            'template',
        ])
            ->with([
                'transactionType' => function ($q) {
                    $q->select('id', 'name', 'name_lang_key', 'code');
                }
            ]);

        if ($filter->search->general) {
            $gFilter = $filter->search->general;
            $query->where(function ($q) use ($gFilter) {
                $q->whereLike('name', '%' . $gFilter . '%')
                    ->orWhereLike('email', '%' . $gFilter . '%')
                    ->orWhereLike('email_subject', '%' . $gFilter . '%')
                    ->orWhereLike('template', '%' . $gFilter . '%');
            });
        }

        if ($filter->search->transactionType) {
            $query->where('transaction_type_id', $filter->search->transactionType);
        }

        $query = $query->orderBy('created_at', 'desc');

        // Clone query untuk total count
        $total = (clone $query)->count();

        // Ambil data paginasi
        $templates = $query
            ->limit($filter->perpage)
            ->offset(($filter->page - 1) * $filter->perpage)
            ->get();

        return ListDto::from([
            'data' => $templates,
            'total' => $total,
        ]);
    }
}
