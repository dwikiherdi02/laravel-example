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
        'resident_id',
        'dues_month_id',
        'parent_id',
        'base_amount',
        'unique_code',
        'final_amount',
        'is_paid',
        'is_merge',

        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function resident(): HasOne
    {
        return $this->hasOne(Resident::class, 'id', 'resident_id');
    }

    public function duesMonth(): HasOne
    {
        return $this->hasOne(DuesMonth::class, 'id', 'dues_month_id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(DuesPayment::class, 'id', 'parent_id');
    }

    public function childs(): HasMany
    {
        return $this->hasMany(DuesPayment::class, 'parent_id', 'id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DuesPaymentDetail::class, 'dues_payment_id', 'id');
    }
}
