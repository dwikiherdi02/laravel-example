<?php

namespace Database\Seeders\Data;

use App\Enum\TransactionStatusEnum;
use Carbon\Carbon;

class TransactionStatusData
{
    /**
     * Get the transaction type data.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            [
                'id' => TransactionStatusEnum::Pending->value,
                'name' => 'Menunggu',
                'name_lang_key' => 'transaction_status.pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => TransactionStatusEnum::Success->value,
                'name' => 'Berhasil',
                'name_lang_key' => 'transaction_status.success',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => TransactionStatusEnum::Failed->value,
                'name' => 'Gagal',
                'name_lang_key' => 'transaction_status.failed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
    }
}
