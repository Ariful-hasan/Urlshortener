<?php

namespace App\Contracts;

interface UrlShortenerContract {
    
    /**
     * makeShortUrl
     *
     * @param  mixed $url
     * @return void
     */
    public function makeShortUrl(string $url);
    
    /**
     * makeHash
     *
     * @return string
     */
    public function makeHash (): string;
}