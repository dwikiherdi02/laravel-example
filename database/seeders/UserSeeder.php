<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => Str::orderedUuid(),
                'name' => 'Admin',
                'username' => 'admin',
                'role_id' => RoleEnum::Admin,
                'resident_id' => null,
                'password' => Hash::make(env('DEFAULT_PASSWORD', '12345678')),
                'is_initial_login' => true,
                'is_protected' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => Str::orderedUuid(),
                'name' => 'Bendahara',
                'username' => 'bendahara',
                'role_id' => RoleEnum::Bendahara,
                'resident_id' => null,
                'password' => Hash::make(env('DEFAULT_PASSWORD', '12345678')),
                'is_initial_login' => true,
                'is_protected' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        User::insert($users);

        /* User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'role_id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
            'password' => Hash::make(env('DEFAULT_PASSWORD', '12345678')),
            'is_initial_login' => true,
        ]); */
    }
}
