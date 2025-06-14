<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'housing_block',
        'phone_number',
        'address',
        'unique_code',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function (Resident $resident) {
            $resident->user->delete(); // delete user first (if exists)

            $resident->unique_code = null;
            $resident->save(); // Simpan perubahan unique_code sebelum soft delete
        });

        static::saving(function (Resident $resident) {
            if ($resident->isDirty('name')) {
                $resident->user()->update(['name' => $resident->name]); // Update user name directly
            }
        });
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'resident_id', 'id');
    }

    public function point(): HasOne
    {
        return $this->hasOne(ResidentPoint::class, 'resident_id', 'id');
    }
}
