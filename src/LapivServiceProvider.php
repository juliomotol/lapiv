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

        $this->registerMacroHelpers();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/lapiv.php', 'lapiv');
    }
    
    protected function registerMacroHelpers()
    {
        if (! method_exists(\Illuminate\Routing\Route::class, 'macro')) { // Lumen
            return;
        }

        Route::macro('lapiv', new LapivRoute);
    }
}
