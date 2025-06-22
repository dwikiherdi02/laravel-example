<?php

namespace App\Services;

use App\Dto\SystemBalanceDto;
use App\Dto\TransactionDto;
use App\Enum\IsMergeEnum;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Repositories\ResidentPointRepository;
use App\Repositories\SystemBalanceRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

class SystemBalanceService
{
    function __construct(
        protected SystemBalanceRepository $systemBalanceRepo,
        protected TransactionRepository $transactionRepo,
        protected ResidentPointRepository $residentPointRepo,
    ) {
    }

    public function getBalance()
    {
        $balance = $this->systemBalanceRepo->first([], ['*'], ['id' => 'desc']);
        if ($balance) {
            return SystemBalanceDto::from($balance);
        }
        return null;
    }

    public function reCalculateBalance(TransactionDto $transactionData): void
    {
        DB::transaction(function () use ($transactionData) {
            $transaction = $this->transactionRepo->findById($transactionData->id);

            if ($transaction && $transaction->transaction_status_id == TransactionStatusEnum::Pending) {
                // ambil data saldo
                $balance = $this->systemBalanceRepo->getLockedBalance();

                if ($transaction->transaction_type_id == TransactionTypeEnum::Credit) {
                    // $balance->amount += $transaction->amount;
                    $balance->total_balance += $transaction->base_amount;
                    $balance->total_point += $transaction->point;
                    $balance->final_balance += $transaction->final_amount;
                } elseif ($transaction->transaction_type_id == TransactionTypeEnum::Debit) {
                    // $balance->amount -= $transaction->amount;
                    $balance->total_balance -= $transaction->base_amount;
                    $balance->total_point -= $transaction->point;
                    $balance->final_balance -= $transaction->final_amount;
                }

                // simpan saldo terbaru
                $balance->save();

                if ($transaction->duesPayment) {
                    $duesPayment = $transaction->duesPayment;

                    // jika iuran ini merupakan merge, lakukan proses merge
                    if ($duesPayment->is_merge != IsMergeEnum::NoMerge) {
                        $duesPaymentChilds = $duesPayment->childs;
                        foreach ($duesPaymentChilds as $duesPaymentChild) {
                            // update status pembayaran iuran
                            $duesPaymentChild->is_paid = true;
                            $duesPaymentChild->save();

                            if ($duesPayment->is_merge == IsMergeEnum::HouseBillMerge) {
                                $resident = $duesPaymentChild->resident;
                                $this->saveResidentPoint($resident->id, $duesPaymentChild->unique_code);
                            }
                        }
                    }

                    // jika iuran ini bukan merge dari beberapa rumah
                    if ($duesPayment->is_merge != IsMergeEnum::HouseBillMerge) {
                        $resident = $duesPayment->resident;
                        $this->saveResidentPoint($resident->id, $duesPayment->unique_code);
                    }

                    // update status pembayaran iuran
                    $duesPayment->is_paid = true;
                    $duesPayment->save();
                }

                // update status transaksi
                $transaction->transaction_status_id = TransactionStatusEnum::Success;
                $transaction->system_balance = $balance->final_balance;
                $transaction->save();
            }

        });
    }

    private function saveResidentPoint($residentId, $point)
    {
        $residentPoint = $this->residentPointRepo->findByResidentId($residentId);
        // simpan point ke user
        if ($residentPoint) {
            $residentPoint->total_point += $point;
            $residentPoint->save();
        } else {
            $this->residentPointRepo->create([
                'resident_id' => $residentId,
                'total_point' => $point,
            ]);
        }
    }
}
