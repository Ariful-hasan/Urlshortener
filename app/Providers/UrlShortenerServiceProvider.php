<?php

namespace App\Providers;

use App\Contracts\UrlShortenerContract;
use App\Utilities\SnowflakeGenerator;
use App\Utilities\UrlShortenerService;
use Illuminate\Support\ServiceProvider;

class UrlShortenerServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the SnowflakeGenerator as a Singleton
        $this->app->singleton(SnowflakeGenerator::class, function ($app) {
            // Grab the machine_id we calculated in the config
            $machineId = config('app.machine_id');

            return new SnowflakeGenerator($machineId);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UrlShortenerContract::class, UrlShortenerService::class);
    }
}
