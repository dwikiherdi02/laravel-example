<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Data\UserData;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert(UserData::get());
    }
}
