<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function menu(): HasOne
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }

    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
