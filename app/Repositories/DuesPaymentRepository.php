<?php

namespace App\Repositories;

use App\Dto\ListDto\ListDto;
use App\Dto\ListDto\ListFilterDto;
use App\Enum\IsMergeEnum;
use App\Models\DuesMonth;
use App\Models\DuesPayment;
use App\Models\Resident;
use Carbon\Carbon;

class DuesPaymentRepository extends Repository
{
    function __construct(
        DuesPayment $model
    ) {
        parent::__construct($model);
    }

    public function list(ListFilterDto $filter): ListDto
    {
        $query = $this->model->select([
            'id',
            'resident_id',
            'dues_month_id',
            'base_amount',
            'unique_code',
            'final_amount',
            'is_paid',
            'is_merge',
        ])
            ->with([
                'duesMonth:id,year,month,contribution_ids',
                'resident:id,name,housing_block,phone_number,address,unique_code',
                'childs:id,resident_id,dues_month_id,parent_id,base_amount,unique_code,final_amount,is_paid',
                'childs.duesMonth:id,year,month,contribution_ids',
                'childs.resident:id,name,housing_block,phone_number,address,unique_code',
            ]);

        /* $query = $query->where(function ($q) {
            $q->whereNull('parent_id')
                ->orWhere('is_merge', true);
        }); */

        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        if ($filter->search->year) {
            $year = $filter->search->year;
        }

        if ($filter->search->month) {
            $month = $filter->search->month;
        }

        $query = $query->whereNull('parent_id')
            ->where(function ($q) use ($year, $month) {
                $q->whereHas('duesMonth', function ($q) use ($year, $month) {
                    $q->where('year', $year)
                        ->where('month', $month);
                })
                    ->orWhereHas('childs.duesMonth', function ($q) use ($year, $month) {
                        $q->where('year', $year)
                            ->where('month', $month);
                    });
            });

        if ($filter->search->isPaid !== null) {
            $query = $query->where('is_paid', $filter->search->isPaid);
        }

        if ($filter->search->authUserId) {
            $authUserId = $filter->search->authUserId;
            $query = $query->where(function ($q) use ($authUserId) {
                $q->whereHas('resident.user', function ($q) use ($authUserId) {
                    $q->where('id', $authUserId);
                })->orWhereHas('childs.resident.user', function ($q) use ($authUserId) {
                    $q->where('id', $authUserId);
                });
            });
        }

        if ($filter->search->general) {
            $gFilter = $filter->search->general;
            $query->where(function ($q) use ($gFilter) {
                $q->whereLike('base_amount', '%' . $gFilter . '%')
                    ->orWhereLike('unique_code', '%' . $gFilter . '%')
                    ->orWhereLike('final_amount', '%' . $gFilter . '%')
                    ->orWhereHas('resident', function ($q) use ($gFilter) {
                        $q->whereLike('name', '%' . $gFilter . '%')
                            ->orWhereLike('housing_block', '%' . $gFilter . '%')
                            ->orWhereLike('phone_number', '%' . $gFilter . '%')
                            ->orWhereLike('address', '%' . $gFilter . '%');
                    })
                    ->orWhereHas('childs.resident', function ($q) use ($gFilter) {
                        $q->whereLike('name', '%' . $gFilter . '%')
                            ->orWhereLike('housing_block', '%' . $gFilter . '%')
                            ->orWhereLike('phone_number', '%' . $gFilter . '%')
                            ->orWhereLike('address', '%' . $gFilter . '%');
                    });
            });
        }

        // $query = $query->orderBy('created_at', 'desc');
        $query = $query->orderBy('id', 'desc');

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

    public function listMergedByYearAndMonth(int $year, int $month)
    {
        $query = $this->model->select([
            'id',
            'resident_id',
            'dues_month_id',
            'base_amount',
            'unique_code',
            'final_amount',
            'is_paid',
            'is_merge',
        ])
            ->with([
                'duesMonth:id,year,month,contribution_ids',
                'resident:id,name,housing_block,phone_number,address,unique_code',
            ])
            ->where('is_paid', false)
            // ->where('is_merge', IsMergeEnum::NoMerge->value)
            ->whereNull('parent_id')
            ->where('is_merge', IsMergeEnum::NoMerge->value)
            ->whereHas(
                'duesMonth',
                function ($q) use ($year, $month) {
                    $q->where('year', $year)
                        ->where('month', $month);
                }
            )
            ->orderBy(Resident::selectRaw('MIN(housing_block)')
                ->whereColumn('dues_payments.resident_id', 'residents.id'));

        return $query->get();
    }

    public function listMergeByResidentId(string $residentId)
    {
        $query = $this->model->select([
            'id',
            'resident_id',
            'dues_month_id',
            'base_amount',
            'unique_code',
            'final_amount',
            'is_paid',
            'is_merge',
        ])
            ->with([
                'duesMonth:id,year,month,contribution_ids',
                'resident:id,name,housing_block,phone_number,address,unique_code',
            ])
            ->where('is_paid', false)
            ->where('resident_id', $residentId)
            ->whereNull('parent_id')
            ->where('is_merge', IsMergeEnum::NoMerge->value)
            ->orderBy(DuesMonth::selectRaw('MIN(year)')
                ->whereColumn('dues_payments.dues_month_id', 'dues_months.id'))
            ->orderBy(DuesMonth::selectRaw('MIN(month)')
                ->whereColumn('dues_payments.dues_month_id', 'dues_months.id'));

        return $query->get();
    }

    public function findByIds(array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }
}
