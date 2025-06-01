<?php

namespace Database\Seeders;

use App\Models\TransactionMethod;
use Database\Seeders\Data\TransactionMethodData;
use Illuminate\Database\Seeder;

class TransactionMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionMethod::insert(TransactionMethodData::get());
    }
}
