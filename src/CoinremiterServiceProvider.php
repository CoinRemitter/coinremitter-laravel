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
        $config = realpath(__DIR__ . '/config/coinremitter.php');

        $this->publishes([
            $config => config_path('coinremitter.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/coinremitter.php',
            'coinremitter'
        );
        if (File::exists(__DIR__ . '/config/coinremitter.php')) {
            require __DIR__ . '/config/coinremitter.php';
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {}
}
