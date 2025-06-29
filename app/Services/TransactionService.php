<?php

namespace App\Services;

use App\Dto\ListDto\ListFilterDto;
use App\Dto\TransactionDto;
use App\Enum\TransactionMethodEnum;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Events\BalanceCalculationRequested;
use App\Events\CancellationBalanceRequested;
use App\Models\TransactionMethod;
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

    public function findById(string $id, bool $isRaw = false)
    {
        $item = $this->transactionRepo->findById($id);
        if ($item != null) {
            if ($isRaw) {
                return $item;
            }
            return TransactionDto::from($item->toArray());
        }
        return null;
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
            $data->name = 'Saldo Masuk - Bayar Iuran (Manual)';
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

    public function cancel(string $id)
    {
        $transaction = $this->transactionRepo->findById($id);

        if (!$transaction) {
            throw new \Exception(trans('Transaksi tidak ditemukan'));
        }

        if ($transaction->transaction_status_id == TransactionStatusEnum::Canceled) {
            throw new \Exception(trans('Transaksi sudah dibatalkan'));
        }

        DB::beginTransaction();
        try {
            $now = Carbon::now();

            $type = $transaction->transaction_type_id == TransactionTypeEnum::Credit
                ? TransactionTypeEnum::Debit
                : TransactionTypeEnum::Credit;

            $name = $type == TransactionTypeEnum::Credit
                ? 'Pengembalian Saldo - Pembatalan Transaksi'
                : 'Pengurangan saldo - Pembatalan Transaksi';

            // Buat transaksi pembatalan (mengembalikan / mengurangi saldo)
            $cancellationData = [
                'parent_id' => $transaction->id,
                'transaction_method_id' => TransactionMethodEnum::Transfer,
                'transaction_type_id' => $type,
                'transaction_status_id' => TransactionStatusEnum::Pending,
                'name' => $name,
                'dues_payment_id' => $transaction->dues_payment_id ?? null,
                'base_amount' => $transaction->base_amount,
                'point' => $transaction->point,
                'final_amount' => $transaction->final_amount,
                'date' => $now,
            ];

            $cancellationTransaction = $this->transactionRepo->create($cancellationData);

            // dd(TransactionDto::from($cancellationTransaction));

            // Ubah status transaksi menjadi dibatalkan
            $transaction->transaction_status_id = TransactionStatusEnum::Canceled;
            $transaction->save();

            DB::commit();

            CancellationBalanceRequested::dispatch($cancellationTransaction);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            throw new \Exception(trans('label.error_save'));
        }
    }
}
