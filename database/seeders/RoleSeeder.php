<?php

namespace Database\Seeders;

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
                'id' => '9ece7992-d2d7-4f56-8c03-3ffdc8db3ef8',
                'name' => 'Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => '9ece7992-ed89-4bdd-8aaf-6607dfe89bef',
                'name' => 'Bendahara',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => '9ece7992-ed91-4d1d-9e7f-66b38b0b7520',
                'name' => 'Warga',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        Role::insert($roles);
    }
}
