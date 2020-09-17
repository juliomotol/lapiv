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
        $prefix = $this->cleanUri(config('lapiv.base_route'));

        if (config('lapiv.default') === 'uri') {
            $prefix .= $this->cleanUri(config('lapiv.method.uri.prefix'));
        }

        $prefix .= $this->cleanUri($prefix);

        return $prefix;
    }

    private function cleanUri($uri)
    {
        return Str::start(trim($uri, '/'), '/');
    }
}
