<?php

namespace Database\Seeders;

use App\Models\SystemBalance;
use Database\Seeders\Data\SystemBalanceData;
use Illuminate\Database\Seeder;

class SystemBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemBalance::insert(SystemBalanceData::get());
    }
}
