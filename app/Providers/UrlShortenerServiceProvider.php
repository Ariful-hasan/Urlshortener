<?php

namespace App\Providers;

use App\Contracts\UrlShortenerContract;
use App\Models\ShortUrl;
use App\Contracts\ShortUrlRepositoryContract;
use App\Utilities\SnowflakeGenerator;
use App\Services\UrlShortenerService;
use Illuminate\Support\ServiceProvider;

class UrlShortenerServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UrlShortenerContract::class, UrlShortenerService::class);

        // Bind the SnowflakeGenerator as a Singleton
        $this->app->singleton(SnowflakeGenerator::class, function ($app) {
            // Grab the machine_id we calculated in the config
            $machineId = config('app.machine_id');

            return new SnowflakeGenerator($machineId);
        });

        $this->app->singleton(ShortUrlRepositoryContract::class, function ($app) {
            $shortUrlRepository = new \App\Repositories\EloquentShortUrlRepository($app->make(ShortUrl::class));

            return new \App\Repositories\CachedShortUrlRepository($shortUrlRepository);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
