<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Imap extends Model
{
    use HasUuids;

    public $table = 'imap';
    
    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'host',
        'port',
        'protocol',
        'encryption',
        'validate_cert',
        'username',
        'password',
        'authentication',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];  
}
