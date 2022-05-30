<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['url', 'hash'];

    protected function Hash(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => config("constants.SHORT_BASE_URL").$value,
        );
    }
}
