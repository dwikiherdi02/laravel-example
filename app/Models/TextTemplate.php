<?php

namespace App\Models;

use App\Enum\TransactionTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextTemplate extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'transaction_type_id',
        'email',
        'email_subject',
        'template',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'transaction_type_id' => TransactionTypeEnum::class,
        ];
    }

    public function transactionType(): HasOne
    {
        return $this->hasOne(TransactionType::class, 'id', 'transaction_type_id');
    }
}

