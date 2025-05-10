<?php

namespace Database\Seeders;

use App\Models\MenuShortcut;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuShortcutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuShortcuts = [
            // Admin
            [
                'menu_id' => '018f984f-5c4d-912d-a2db-00a465da3f17',
                'role_id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-74cb-9341-31a06b55de57',
                'role_id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-7b7a-a54d-bba9e4d83d03',
                'role_id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
                'sort' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-7ed6-83f5-e7e80adfe98b',
                'role_id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
                'sort' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Bendahara
            [
                'menu_id' => '018f984f-5c4d-912d-a2db-00a465da3f17',
                'role_id' => '9ece7992-ed89-4bdd-8aaf-6607dfe89bef',
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-80fa-b27a-67f61ecb5c87',
                'role_id' => '9ece7992-ed89-4bdd-8aaf-6607dfe89bef',
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-8368-b07e-9e7b58fc998f',
                'role_id' => '9ece7992-ed89-4bdd-8aaf-6607dfe89bef',
                'sort' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-860f-92c7-20636b831aa2',
                'role_id' => '9ece7992-ed89-4bdd-8aaf-6607dfe89bef',
                'sort' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Warga
            [
                'menu_id' => '018f984f-5c4d-912d-a2db-00a465da3f17',
                'role_id' => '9ece7992-ed91-4d1d-9e7f-66b38b0b7520',
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'menu_id' => '018f984f-5c4d-8812-910f-c8c19a51952f',
                'role_id' => '9ece7992-ed91-4d1d-9e7f-66b38b0b7520',
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        MenuShortcut::insert($menuShortcuts);
    }
}
