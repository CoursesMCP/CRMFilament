<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Address extends Model
{
    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'street',
        'city',
        'state',
        'country',
        'zip_code',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $parts = [];

                if ($this->street) {
                    $parts[] = $this->street;
                }
                if ($this->city) {
                    $parts[] = Str::ucfirst($this->city);
                }
                if ($this->state) {
                    $parts[] = Str::ucfirst($this->state);
                }
                if ($this->country) {
                    $parts[] = Str::ucfirst($this->country);
                }
                if ($this->zip_code) {
                    $parts[] = $this->zip_code;
                }

                return implode(', ', $parts);
            },
        );
    }
}
