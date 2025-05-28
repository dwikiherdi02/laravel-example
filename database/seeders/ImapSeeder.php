<?php

namespace Database\Seeders;

use App\Models\Imap;
use Database\Seeders\Data\ImapData;
use Illuminate\Database\Seeder;

class ImapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Imap::insert(ImapData::get());
    }
}
