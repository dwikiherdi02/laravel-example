<?php

namespace Database\Seeders\Data;

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
                'id' => '9f0e38bd-21de-4834-971a-b076f38383d9',
                'name' => 'Tunai',
                'name_lang_key' => 'transaction_method.cash',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => '9f0e38bd-21e8-423b-8637-59e758e3d54a',
                'name' => 'Non Tunai',
                'name_lang_key' => 'transaction_method.transfer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
    }
}
