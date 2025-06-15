<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Dto\TransactionDto;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Events\BalanceCalculationRequested;
use App\Repositories\DuesPaymentRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    function __construct(
        protected TransactionRepository $transactionRepo,
        protected DuesPaymentRepository $duesPaymentRepo,
    ) {
    }

    public function list(ListFilterDto $filter)
    {
        return $this->transactionRepo->list($filter);
    }

    public function create(TransactionDto $data)
    {
        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $data->transaction_status_id = TransactionStatusEnum::Pending;
            $data->point = 0;
            $data->final_amount = $data->base_amount;
            $data->date = $now;
            $transaction = $this->transactionRepo->create($data->toArray());
            // dd($transaction->toArray());
            DB::commit();
            BalanceCalculationRequested::dispatch($transaction);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }
    }

    public function createMarkAsPaid(TransactionDto $data)
    {
        $duesPayment = $this->duesPaymentRepo->findById($data->dues_payment_id);
        if ($duesPayment == null) {
            throw new \Exception(trans('dues_payment.error_payment_not_found'));
        }

        DB::beginTransaction();
        try {
            $now = Carbon::now();
            $data->name = 'Bayar Iuran (Manual)';
            $data->transaction_type_id = TransactionTypeEnum::Credit;
            $data->transaction_status_id = TransactionStatusEnum::Pending;
            $data->base_amount = $duesPayment->base_amount;
            $data->point = $duesPayment->unique_code;
            $data->final_amount = $duesPayment->final_amount;
            $data->date = $now;
            $transaction = $this->transactionRepo->create($data->toArray());
            DB::commit();
            BalanceCalculationRequested::dispatch($transaction);
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
