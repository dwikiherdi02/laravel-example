<?php

namespace Database\Seeders\Data;

use App\Enum\TransactionMethodEnum;
use Carbon\Carbon;
use Str;

class TransactionMethodData
{
    /**
     * Get the transaction method data.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            [
                'id' => TransactionMethodEnum::Cash->value,
                'name' => 'Tunai',
                'name_lang_key' => 'transaction_method.cash',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => TransactionMethodEnum::Transfer->value,
                'name' => 'Non Tunai',
                'name_lang_key' => 'transaction_method.transfer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
    }
}
