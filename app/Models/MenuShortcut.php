<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Model;

class MenuShortcut extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'menu_id',
        'role_id',
        'sort',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'role_id' => RoleEnum::class,
        ];
    }

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
        return $this->role_id instanceof RoleEnum ? $this->role_id->value : $this->role;
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
