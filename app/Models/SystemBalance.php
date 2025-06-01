<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class SystemBalance extends Model
{
    use HasUuids;

    public $table = 'system_balance';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'total_balance',
        'total_point',
        'final_balance',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
