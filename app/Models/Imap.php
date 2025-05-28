<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Imap extends Model
{
    public $table = 'imap';
    
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'host',
        'port',
        'protocol',
        'encryption',
        'validate_cert',
        'username',
        'password',
        'authentication',
    ];
}
