<?php

namespace App\Utilities;

use App\Contracts\UrlShortenerContract;
use App\Models\Link;
use App\Utilities\UrlValidationService;

class UrlShortenerService implements UrlShortenerContract {
    
    /**
     * makeShortUrl
     *
     * @param  mixed $url
     * @return void
     */
    public function makeShortUrl(string $url)
    {  
        try {

            $link = Link::where('url', $url)->first();
            if ($link) {
                return response()->json([
                    config('constants.STATUS_CODE') => "200",
                    config('constants.MESSAGE') => "success",
                    'data' => ['original_url' => $link->url, 'short_url' => $link->hash],
                ], 200);
            }

            if (!UrlValidationService::isSafeUrl($url)){
                return response()->json([
                    config('constants.STATUS_CODE') => "503",
                    config('constants.MESSAGE') => config('status.status_code.503'),
                    config('constants.ERROR') => "",
                ], 503);
            }

            $link = Link::create([
                    'url' => $url,
                    'hash'=> $this->makeHash()
                ]);

            return response()->json([
                config('constants.STATUS_CODE') => "200",
                config('constants.MESSAGE') => "success",
                'data' => ['original_url' => $link->url, 'short_url' => $link->hash],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                config('constants.STATUS_CODE') => "500",
                config('constants.MESSAGE') => config('status.status_code.500'),
                config('constants.ERROR') => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * makeHash
     *
     * @return string
     */
    public function makeHash(): string
    {
        return bin2hex(random_bytes(3));
    }



}