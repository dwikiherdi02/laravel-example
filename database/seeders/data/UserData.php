<?php

namespace Database\Seeders\Data;

use App\Enum\RoleEnum;
use Carbon\Carbon;
use Hash;
use Str;

class UserData
{
    /**
     * Get the user data for seeding.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            [
                'id' => Str::uuid7(),
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
                'id' => Str::uuid7(),
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
    }
}
