<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuRole extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'menu_id',
        'role_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
