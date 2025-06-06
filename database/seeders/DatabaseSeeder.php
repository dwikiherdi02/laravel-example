<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MenuGroupSeeder::class,
            MenuSeeder::class,
            MenuRoleSeeder::class,
            MenuShortcutSeeder::class,
            ImapSeeder::class,
            TransactionTypeSeeder::class,
            TransactionMethodSeeder::class,
            TransactionStatusSeeder::class,
            SystemBalanceSeeder::class,
        ]);
    }
}
