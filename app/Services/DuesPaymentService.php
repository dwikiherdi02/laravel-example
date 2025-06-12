<?php

namespace App\Services;

use App\Dto\DuesPaymentDto;
use App\Dto\ListDto\ListFilterDto;
use App\Dto\MonthlyDuesHistoryDto;
use App\Enum\IsMergeEnum;
use App\Enum\RoleEnum;
use App\Repositories\DuesPaymentRepository;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DuesPaymentService
{
    function __construct(
        protected DuesPaymentRepository $duesPaymentRepo,
    ) {
    }

    public function list(ListFilterDto $filter)
    {
        // jika user login adalah warga, data  yang ditampilkan hanya yang sesuai dengan user tersebut
        // jika user login adalah admin atau bendahara, tampilkan semua data
        if (auth_role() === RoleEnum::Warga) {
            $filter->search->authUserId = auth()->id();
        }
        ;
        return $this->duesPaymentRepo->list($filter);
    }

    public function findById(string $id)
    {
        $item = $this->duesPaymentRepo->findById($id);

        if ($item) {
            return $item;
        }

        return null;
    }

    public function listMergedByYearAndMonth(int $year, int $month)
    {
        return $this->duesPaymentRepo->listMergedByYearAndMonth($year, $month);
    }

    public function listMergeByResidentId(string $residentId)
    {
        return $this->duesPaymentRepo->listMergeByResidentId($residentId);
    }

    public function createMerge(MonthlyDuesHistoryDto $data)
    {
        if (empty($data->dues_payment_ids)) {
            throw new \Exception(trans('label.error_not_selected_item', ['attribute' => trans('dues_payment.label_bill_list')]));
        }

        if (count($data->dues_payment_ids) < 2) {
            throw new \Exception(trans( 'dues_payment.error_minimum_selected', ['attribute' => trans('dues_payment.label_bill_list') ]));
        }

        if ($data->is_merge == null) {
            throw new \Exception('dues_payment.error_is_merge_not_found');
        }

        $selectedDuesPayments = $this->duesPaymentRepo->findByIds($data->dues_payment_ids);
        if ($selectedDuesPayments->count() < 1) {
            throw new \Exception(trans('label.error_requested_data_not_found', ['attribute' => trans('dues_payment.label_bill_list')]));
        }

        DB::beginTransaction();
        try {
            $baseAmount = $selectedDuesPayments->pluck('base_amount')->sum();
            $uniqueCode = $selectedDuesPayments->first()->unique_code;
            if ($data->is_merge == IsMergeEnum::HouseBillMerge) {
                $uniqueCode = $selectedDuesPayments->pluck('unique_code')->sum();
            }

            // tambah data dues payment parent
            $parentDuesPaymentData = DuesPaymentDto::from([
                'resident_id' => $data->is_merge == IsMergeEnum::MonthlyBillMerge ? $selectedDuesPayments->first()->resident_id : null,
                'dues_month_id' => $data->is_merge == IsMergeEnum::HouseBillMerge ? $selectedDuesPayments->first()->dues_month_id : null,
                'parent_id' => null,
                'base_amount' => $baseAmount,
                'unique_code' => $uniqueCode,
                'final_amount' => array_sum([
                    $baseAmount,
                    $uniqueCode
                ]),
                'is_paid' => false,
                'is_merge' => $data->is_merge->value,
            ])->toArray();
            
            $parentDuesPayment = $this->duesPaymentRepo->create($parentDuesPaymentData);

            //  update data dues payment yang sudah dipilih
            $this->duesPaymentRepo->updateMany(
                ['id' => $selectedDuesPayments->pluck('id')->toArray()],
                [
                    'parent_id' => $parentDuesPayment->id,
                ]
            );

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }
    }
}
