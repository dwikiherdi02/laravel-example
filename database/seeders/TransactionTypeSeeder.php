<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Database\Seeders\Data\TransactionTypeData;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionType::insert(TransactionTypeData::get());
    }
}
