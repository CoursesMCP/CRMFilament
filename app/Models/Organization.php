<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\AddressTrait;

class Organization extends Model
{
    use HasUuids, SoftDeletes, AddressTrait;

    protected $fillable = [
        'name',
        'description',
        'nit',
        'cellphone',
        'phone',
        'email',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
