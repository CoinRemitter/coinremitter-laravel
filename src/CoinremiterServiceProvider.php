<?php

namespace Coinremitter;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class CoinremiterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/coinremitter.php' => config_path('coinremitter.php'),
        ], 'config');

        // Only merge config if the user has already published it
        if (file_exists(config_path('coinremitter.php'))) {
            $this->mergeConfigFrom(
                __DIR__ . '/config/coinremitter.php',
                'coinremitter'
            );
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge default package config on every request (safe fallback)
        // This ensures config values exist even before publishing
        $this->mergeConfigFrom(
            __DIR__ . '/config/coinremitter.php',
            'coinremitter'
        );
    }
}
