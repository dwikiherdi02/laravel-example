<?php

namespace Database\Seeders\Data;

use Carbon\Carbon;
use Str;

class ImapData
{
    /**
     * Get the user data for seeding.
     *
     * @return array
     */
    public static function get(): array
    {
        return [
            'id' => Str::uuid7(),
            'host' => 'imap.gmail.com',
            'port' => 993,
            'protocol' => 'imap',
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => null,
            'password' => null,
            'authentication' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
