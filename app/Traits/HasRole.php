<?php

namespace App\Traits;

use App\Enum\RoleEnum;

trait HasRole
{
    /**
     * The attributes that are mass assignable or accessible on the User model.
     *
     * This property defines which fields can be set via mass assignment,
     * providing a safeguard against mass-assignment vulnerabilities.
     * Typically, you should list only the fields that are safe to be filled
     * by user input, such as 'name', 'email', and 'password'.
     *
     * @var array
     */
    public function roleValue(): string
    {
        return $this->role_id instanceof RoleEnum ? $this->role_id->value : $this->role_id;
    }

    public function isRole(RoleEnum $role): bool
    {
        return $this->role_id === $role;
    }

    public function isAdmin(): bool
    {
        return $this->isRole(RoleEnum::Admin);
    }

    public function isBendahara(): bool
    {
        return $this->isRole(RoleEnum::Bendahara);
    }

    public function isWarga(): bool
    {
        return $this->isRole(RoleEnum::Warga);
    }
}
