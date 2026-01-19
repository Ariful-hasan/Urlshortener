<?php

namespace App\Http\Controllers;

use App\Contracts\UrlShortenerContract;
use Illuminate\Http\Request;
use App\Http\Requests\UrlShortenerRequest;
use App\Models\ShortUrl;
use Illuminate\Http\JsonResponse;

class ShortUrlController extends Controller
{
    public function __construct(private UrlShortenerContract $urlShortener)
    {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : void
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() : void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UrlShortenerRequest $request) : JsonResponse
    {
        $validated = $request->validated();
        $shortUrl = $this->urlShortener->makeShortUrl($validated['url']);

        return response()->json([
            config('constants.SUCCESS') => true,
            config('constants.MESSAGE') => "success",
            config('constants.DATA') => [
                'original_url' => $shortUrl->url,
                'short_url' => $shortUrl->shortCode
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function show(string $shortCode): JsonResponse
    {
        $shortUrl = $this->urlShortener->getOriginalUrl($shortCode);

        return response()->json([
            config('constants.SUCCESS') => true,
            config('constants.MESSAGE') => "success",
            config('constants.DATA') => [
                'original_url' => $shortUrl->url,
                'short_url' => $shortUrl->shortCode
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function edit(ShortUrl $shortUrl) : void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShortUrl $shortUrl) : void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShortUrl $shortUrl) : void
    {
        //
    }
}
