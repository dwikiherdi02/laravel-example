<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DuesPaymentDetail extends Model
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
        'dues_payment_id',
        'contribution_id',
        'amount',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function duesPayment(): HasOne
    {
        return $this->hasOne(DuesPayment::class, 'id', 'dues_payment_id');
    }

    public function contribution(): HasOne
    {
        return $this->hasOne(Contribution::class, 'id', 'contribution_id');
    }
}
