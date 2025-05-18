<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'id' => RoleEnum::Admin->value,
                'name' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => RoleEnum::Bendahara->value,
                'name' => 'Bendahara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => RoleEnum::Warga->value,
                'name' => 'Warga',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        Role::insert($roles);
    }
}
