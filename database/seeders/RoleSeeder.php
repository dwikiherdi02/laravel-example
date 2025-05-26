<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Seeders\Data\RoleData;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert(RoleData::get());
    }
}
