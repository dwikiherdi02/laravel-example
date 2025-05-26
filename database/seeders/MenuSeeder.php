<?php

namespace Database\Seeders;

use App\Models\Menu;
use Database\Seeders\Data\MenuData;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert(MenuData::get());
    }
}
