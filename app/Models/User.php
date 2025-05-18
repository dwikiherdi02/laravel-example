<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'resident_id',
        'name',
        // 'email',
        'username',
        'password',
        'is_initial_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime',
            'password' => 'hashed',
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

    /**
     * Relationship with the Role model.
     */
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function resident(): HasOne
    {
        return $this->hasOne(Resident::class, 'id', 'resident_id');
    }
}
