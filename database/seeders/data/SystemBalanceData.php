<?php

namespace Database\Seeders\Data;

use Carbon\Carbon;
use Str;

class SystemBalanceData
{
    /**
     * Get the system balance data.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            [
                'id' => Str::uuid7(),
                'total_balance' => 0.00,
                'total_point' => 0,
                'final_balance' => 0.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];
    }
}
