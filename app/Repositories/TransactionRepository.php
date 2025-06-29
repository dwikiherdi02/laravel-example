<?php

namespace App\Repositories;

use App\Dto\ListDto\ListDto;
use App\Dto\ListDto\ListFilterDto;
use App\Models\Transaction;
use App\Repositories\Repository;

class TransactionRepository extends Repository
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function list(ListFilterDto $filter): ListDto
    {
        $query = $this->model->select([
            'id',
            'parent_id',
            'transaction_method_id',
            'transaction_type_id',
            'transaction_status_id',
            'name',
            'dues_payment_id',
            'email_id',
            'base_amount',
            'point',
            'final_amount',
            'date',
            'info',
        ])
            ->with([
                'method:id,name,name_lang_key',
                'type:id,name,name_lang_key,code',
                'status:id,name,name_lang_key',
                'child:id,parent_id,transaction_method_id,transaction_type_id,transaction_status_id,name,dues_payment_id,email_id,base_amount,point,final_amount,date,info',
                'parent:id,parent_id,transaction_method_id,transaction_type_id,transaction_status_id,name,dues_payment_id,email_id,base_amount,point,final_amount,date,info',
            ]);

        if ($filter->search->general) {
            $gFilter = $filter->search->general;
            $query->where(function ($q) use ($gFilter) {
                $q->whereLike('name', '%' . $gFilter . '%');
            });
        }

        if ($filter->search->transactionDate) {
            $query->whereDate('date', $filter->search->transactionDate);
        }

        if ($filter->search->transactionType) {
            $query = $query->where('transaction_type_id', $filter->search->transactionType);
        }

        if ($filter->search->transactionMethod) {
            $query = $query->where('transaction_method_id', $filter->search->transactionMethod);
        }

        if ($filter->search->transactionStatus) {
            $query = $query->where('transaction_status_id', $filter->search->transactionStatus);
        }


        $query = $query->orderBy('date', 'desc');

        // Clone query untuk total count
        $total = (clone $query)->count();

        // dd($query->toSql(), $query->getBindings());

        // Ambil data paginasi
        $histories = $query
            ->limit($filter->perpage)
            ->offset(($filter->page - 1) * $filter->perpage)
            ->get();

        return ListDto::from([
            'data' => $histories,
            'total' => $total,
        ]);
    }
}
