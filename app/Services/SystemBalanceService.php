<?php

namespace App\Services;

use App\Dto\TransactionDto;
use App\Enum\IsMergeEnum;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Repositories\SystemBalanceRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserPointRepository;
use Illuminate\Support\Facades\DB;

class SystemBalanceService
{
    function __construct(
        protected SystemBalanceRepository $systemBalanceRepo,
        protected TransactionRepository $transactionRepo,
        protected UserPointRepository $userPointRepo,
    ) {
    }

    public function reCalculateBalance(TransactionDto $transactionData): void
    {
        DB::transaction(function () use ($transactionData) {
            $transaction = $this->transactionRepo->findById($transactionData->id);

            if ($transaction && $transaction->transaction_status_id == TransactionStatusEnum::Pending) {
                // ambil data saldo
                $balance = $this->systemBalanceRepo->getLockedBalance();

                if ($transaction->transaction_type_id == TransactionTypeEnum::Credit) {
                    $balance->amount += $transaction->amount;
                } elseif ($transaction->transaction_type_id == TransactionTypeEnum::Debit) {
                    $balance->amount -= $transaction->amount;
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
                                $user = $duesPaymentChild->resident->user;
                                $this->saveUserPoint($user->id, $duesPaymentChild->unique_code);
                            }
                        }
                    }

                    // jika iuran ini bukan merge dari beberapa rumah
                    if ($duesPayment->is_merge != IsMergeEnum::HouseBillMerge) {
                        $user = $duesPayment->resident->user;
                        $this->saveUserPoint($user->id, $duesPayment->unique_code);
                    }

                    // update status pembayaran iuran
                    $duesPayment->is_paid = true;
                    $duesPayment->save();
                }

                // update status transaksi
                $transaction->transaction_status_id = TransactionStatusEnum::Success;
                $transaction->save();
            }

        });
    }

    private function saveUserPoint($userId, $point)
    {
        $userPoint = $this->userPointRepo->findById($userId);
        // simpan point ke user
        if ($userPoint) {
            $userPoint->total_point += $point;
            $userPoint->save();
        } else {
            $this->userPointRepo->create([
                'user_id' => $userId,
                'total_point' => $point,
            ]);
        }
    }
}
