<?php

namespace App\Repositories;

use App\Models\CodePool;

class EloquentCodePoolRepository
{
    protected $urlShortenerService;

    public function __construct(private CodePool $codePool)
    {}
}
