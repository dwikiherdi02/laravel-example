<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class MenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'id' => 'b0ec98ed-0228-453d-a1f6-7bbee48ecda2',
                'name' => 'Umum',
                'name_lang_key' => 'menu_group.general',
                'sort' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 'cb85dfba-6ac2-4b5b-a012-63bdd379ceb0',
                'name' => 'Kelola Iuran & Biaya',
                'name_lang_key' => 'menu_group.manage_fee',
                'sort' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => '870509a1-faaf-41d8-a832-53e7a37dbf9a',
                'name' => 'Riwayat',
                'name_lang_key' => 'menu_group.history',
                'sort' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => 'add1f6d1-95f2-4fc7-88cc-9242d61bed2d',
                'name' => 'Laporan',
                'name_lang_key' => 'menu_group.report',
                'sort' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => '08d1306e-1497-4eb8-bf46-5f27a8900c6e',
                'name' => 'Pengaturan',
                'name_lang_key' => 'menu_group.settings',
                'sort' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ];

        MenuGroup::insert($groups);
    }
}
