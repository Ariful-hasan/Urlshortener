<?php

use App\Models\CodePool;

class CodePoolRepository
{
    protected $urlShortenerService;

    public function __construct(private CodePool $codePool)
    {}

    public function create(string $shortCode): CodePool
    {
        return $this->codePool->create([
            'code' => $shortCode
        ]);
    }
}
