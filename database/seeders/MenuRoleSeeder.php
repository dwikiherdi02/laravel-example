<?php

namespace Database\Seeders;

use App\Models\MenuRole;
use Database\Seeders\Data\MenuRoleData;
use Illuminate\Database\Seeder;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuRole::insert(MenuRoleData::get());
    }
}
