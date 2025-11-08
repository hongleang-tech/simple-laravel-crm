<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'address_1',
        'address_2',
        'postcode',
        'suburb',
        'state',
        'country',
        'user_id'
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function fullAddress(): Attribute
    {
        $address2 = $this->address_2 ? $this->address_2 . ', ' : '';
        return Attribute::get(function () use ($address2) {
            return "{$this->address_1}, {$address2} {$this->suburb}, {$this->state}, {$this->postcode}, {$this->country}";
        });
    }
}
