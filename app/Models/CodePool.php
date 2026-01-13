<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodePool extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
    ];
}
