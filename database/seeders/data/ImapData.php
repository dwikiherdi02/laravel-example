<?php

namespace Database\Seeders\Data;

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
            'host' => 'imap.gmail.com',
            'port' => 993,
            'protocol' => 'imap',
            'encryption' => 'ssl',
            'validate_cert' => true,
            'username' => null,
            'password' => null,
            'authentication' => null,
        ];
    }
}
