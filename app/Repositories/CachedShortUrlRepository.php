<?php

namespace App\Repositories;

use App\Contracts\ShortUrlRepositoryContract;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Cache;

class CachedShortUrlRepository implements ShortUrlRepositoryContract
{
    public final const CACHE_KEY_URL_HASH = "url:hash:";
    public final const CACHE_KEY_SHORT_URL = "short:url:";
    public final const CACHE_TTL = 3600;

    public function __construct(private EloquentShortUrlRepository $shortUrlRepository)
    {}

    public function findByUrlHash(string $urlHash): ?ShortUrl
    {
        return Cache::remember(
            self::CACHE_KEY_URL_HASH . "{$urlHash}",
            self::CACHE_TTL,
             function () use ($urlHash) {
            return $this->shortUrlRepository->findByUrlHash($urlHash);
        });
    }

    public function create(string $url, string $hash): ShortUrl
    {
        $shortUrl =  $this->shortUrlRepository->create($url, $hash);

        Cache::put(
            self::CACHE_KEY_URL_HASH . "{$hash}",
            $shortUrl,
            self::CACHE_TTL
        );

        Cache::put(
            self::CACHE_KEY_SHORT_URL . "{$shortUrl->code}",
            $shortUrl->url,
            self::CACHE_TTL
        );

        return $shortUrl;
    }
}
