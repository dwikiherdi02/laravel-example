<?php

namespace Database\Seeders;

use App\Models\MenuShortcut;
use Database\Seeders\Data\MenuShortcutData;
use Illuminate\Database\Seeder;

class MenuShortcutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuShortcut::insert(MenuShortcutData::get());
    }
}
