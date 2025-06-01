<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DuesPayment extends Model
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
        'dues_month_id',
        'parent_id',
        'base_amount',
        'unique_code',
        'final_amount',
        'is_paid',
        'is_merge',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function duesMonth(): HasOne
    {
        return $this->hasOne(DuesMonth::class, 'id', 'dues_month_id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(DuesPayment::class, 'id', 'parent_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DuesPaymentDetail::class, 'dues_payment_id', 'id');
    }
}
