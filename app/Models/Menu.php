<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'name_lang_key',
        'slug',
        'icon',
        'menu_group_id',
        'sort',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function menuGroup(): HasOne
    {
        return $this->hasOne(MenuGroup::class, 'id', 'menu_group_id');
    }

    public function menuRole(): HasOne
    {
        return $this->hasOne(MenuRole::class, 'menu_id', 'id');
    }

    public function menuShortcut(): HasOne
    {
        return $this->hasOne(MenuShortcut::class, 'menu_id', 'id');
    }
}
