<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'country'
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
