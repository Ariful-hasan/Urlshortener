<?php

namespace App\Services;

use App\Contracts\ShortUrlRepositoryContract;
use App\Contracts\UrlShortenerContract;
use App\Exceptions\UnsafeUrlException;
use App\Models\ShortUrl;
use App\Utilities\UrlValidationService;
use Illuminate\Http\JsonResponse;

class UrlShortenerService implements UrlShortenerContract {

    public function __construct(
        protected UrlValidationService $urlValidationService,
        protected ShortUrlRepositoryContract $shortUrlRepository
    )
    {}

    /**
     * makeShortUrl
     *
     * @param  string $url
     * @return JsonResponse
     */
    public function makeShortUrl(string $url): ShortUrl
    {
        $hash = $this->makeHash($url);
        $shortUrl = $this->shortUrlRepository->findByUrlHash($hash);

        if ($shortUrl) {
            return $shortUrl;
        }

        if (!$this->urlValidationService->isSafeUrl($url)) {
            throw new UnsafeUrlException();
        }

        return $this->shortUrlRepository->create($url, $hash);
    }

    /**
     * makeHash
     *
     * @return string
     */
    public function makeHash(string $url): string
    {
        return hash('sha256', $url);
    }

    public function getOriginalUrl(string $shortCode) : ShortUrl
    {
        return $this->shortUrlRepository->findByShortCode($shortCode);
    }

}
