<?php

namespace Database\Seeders\Data;

use App\Enum\TransactionTypeEnum;
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
                'id' => TransactionTypeEnum::Credit->value,
                'name' => 'Pemasukan',
                'name_lang_key' => 'transaction_type.credit',
                'code' => 'cr',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => TransactionTypeEnum::Debit->value,
                'name' => 'Pengeluaran',
                'name_lang_key' => 'transaction_type.debit',
                'code' => 'db',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
    }
}
