<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuGroup extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'name_lang_key',
        'sort',        
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function menus(): HasMany 
    {
        return $this->hasMany(Menu::class, 'menu_group_id', 'id');
    }
}
