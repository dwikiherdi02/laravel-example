<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Database\Seeders\Data\TransactionStatusData;
use Illuminate\Database\Seeder;

class TransactionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionStatus::insert(TransactionStatusData::get());
    }
}
