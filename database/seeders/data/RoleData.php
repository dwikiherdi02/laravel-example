<?php

namespace Database\Seeders\Data;

use App\Enum\RoleEnum;
use Carbon\Carbon;

class RoleData
{
    /**
     * Get the role data.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            [
                'id' => RoleEnum::Admin->value,
                'name' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => RoleEnum::Bendahara->value,
                'name' => 'Bendahara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => RoleEnum::Warga->value,
                'name' => 'Warga',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];
    }
}
