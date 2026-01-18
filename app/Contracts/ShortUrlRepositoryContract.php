<?php

namespace App\Contracts;

use App\Models\ShortUrl;

interface ShortUrlRepositoryContract
{
    public function findByUrlHash(string $urlHash): ?ShortUrl;

    public function create(string $url, string $hash): ShortUrl;

    public function findByShortCode(string $shortCode): ShortUrl;
}
