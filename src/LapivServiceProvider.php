<?php

namespace JulioMotol\Lapiv;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LapivServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/lapiv.php' => config_path('lapiv.php'),
            ], 'config');
        }
        
        Route::macro('lapiv', new LapivRoute);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lapiv.php', 'lapiv');
    }
}
