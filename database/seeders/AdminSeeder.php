<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'role_id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
            'password' => Hash::make(env('DEFAULT_PASSWORD', '12345678')),
            'is_initial_login' => true,
        ]);
    }
}
