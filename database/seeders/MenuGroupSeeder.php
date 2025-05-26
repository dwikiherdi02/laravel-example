<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Database\Seeders\Data\MenuGroupData;
use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuGroup::insert(MenuGroupData::get());
    }
}
