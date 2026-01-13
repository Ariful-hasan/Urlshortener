<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    protected $fillable = [
        'url',
        'url_hash',
        'code',
    ];

    public function shortCode() : Attribute
    {
        return Attribute::make(
            get: fn () => config("constants.SHORT_BASE_URL") . $this->code
        );
    }
}
