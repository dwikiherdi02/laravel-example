<?php

namespace Database\Seeders\Data;

use App\Enum\RoleEnum;
use Carbon\Carbon;

class MenuShortcutData
{
    public static function get(): array
    {
        return [
            // Admin
            [
                'menu_id' => '018f984f-5c4d-912d-a2db-00a465da3f17',
                'role_id' => RoleEnum::Admin,
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            /* [
                'menu_id' => '018f984f-5c4d-74cb-9341-31a06b55de57',
                'role_id' => RoleEnum::Admin,
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ], */
            [
                'menu_id' => '018f984f-5c4d-7b7a-a54d-bba9e4d83d03',
                'role_id' => RoleEnum::Admin,
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-7ed6-83f5-e7e80adfe98b',
                'role_id' => RoleEnum::Admin,
                'sort' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-80fa-b27a-67f61ecb5c87',
                'role_id' => RoleEnum::Admin,
                'sort' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Bendahara
            [
                'menu_id' => '018f984f-5c4d-912d-a2db-00a465da3f17',
                'role_id' => RoleEnum::Bendahara,
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-80fa-b27a-67f61ecb5c87',
                'role_id' => RoleEnum::Bendahara,
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-8368-b07e-9e7b58fc998f',
                'role_id' => RoleEnum::Bendahara,
                'sort' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-860f-92c7-20636b831aa2',
                'role_id' => RoleEnum::Bendahara,
                'sort' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Warga
            [
                'menu_id' => '018f984f-5c4d-912d-a2db-00a465da3f17',
                'role_id' => RoleEnum::Warga,
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-8812-910f-c8c19a51952f',
                'role_id' => RoleEnum::Warga,
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
    }
}
