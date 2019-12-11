<?php

namespace Coinremitter;

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
        //
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
        //
        $this->mergeConfigFrom(__DIR__.'/config/coinremitter.php', 'coinremitter');
        $this->publishes([
            __DIR__.'/config' => base_path('config'),
        ]);

    }
}
