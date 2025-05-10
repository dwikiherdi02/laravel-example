<?php

namespace App\Models;

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
}
