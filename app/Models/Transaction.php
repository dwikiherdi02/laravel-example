<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'transaction_method_id',
        'transaction_type_id',
        'dues_payment_id',
        'email_id',
        'account_name',
        'amount',
        'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function transactionMethod(): HasOne
    {
        return $this->hasOne(TransactionMethod::class, 'id', 'transaction_method_id');
    }

    public function transactionType(): HasOne
    {
        return $this->hasOne(TransactionType::class, 'id', 'transaction_type_id');
    }

    public function duesPayment(): HasOne
    {
        return $this->hasOne(DuesPayment::class, 'id', 'dues_payment_id');
    }

    public function email(): HasOne
    {
        return $this->hasOne(Email::class, 'id', 'email_id');
    }
}
