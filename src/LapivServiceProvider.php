<?php

namespace JulioMotol\Lapiv;

use Closure;
use Illuminate\Routing\Router;
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
                __DIR__ . '/../config/lapiv.php' => config_path('lapiv.php'),
            ], 'config');
        }

        $this->registerRouteMacro();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/lapiv.php', 'lapiv');

        $this->app->singleton('lapiv', fn ($app) => new ApiVersioningManager($app));
    }

    protected function registerRouteMacro(): void
    {
        Router::macro('lapiv', fn (Closure $callback) => Lapiv::routeGroup($callback));
    }
}
