<?php

namespace App\Contracts;

use App\Models\ShortUrl;

interface UrlShortenerContract {

    /**
     * makeShortUrl
     *
     * @param  mixed $url
     * @return ShortUrl
     */
    public function makeShortUrl(string $url): ShortUrl;

    /**
     * makeHash
     *
     * @return string
     */
    public function makeHash (string $url): string;

    public function getOriginalUrl(string $shortCode) : ShortUrl;
}
