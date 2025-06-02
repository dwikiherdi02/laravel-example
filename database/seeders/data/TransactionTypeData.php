<?php

namespace Database\Seeders\Data;

use Carbon\Carbon;
use Str;

class TransactionTypeData
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
                'id' => '9f0e38bd-1ea4-432f-8f8f-a34fb5fd7ca8',
                'name' => 'Pemasukan',
                'name_lang_key' => 'transaction_type.credit',
                'code' => 'cr',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => '9f0e38bd-1eb2-42be-8246-31e47e740b97',
                'name' => 'Pengeluaran',
                'name_lang_key' => 'transaction_type.debit',
                'code' => 'db',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
    }
}
