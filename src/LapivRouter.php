<?php

namespace JulioMotol\Lapiv;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class LapivRouter
{
    public function __invoke($prefix, $namespace = null, $callback = null)
    {
        Route::namespace($this->getNamespace($namespace))
            ->prefix($this->getPrefix($prefix))
            ->group($callback);
    }

    private function getNamespace($namespace)
    {
        return config('lapiv.base_namespace').Str::start($namespace, '\\');
    }

    private function getPrefix($prefix)
    {
        return Str::start('lapiv.base_route', '/').Str::start('lapiv.uri.prefix', '/').Str::start($prefix, '/');
    }
}
