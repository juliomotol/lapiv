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
        $newPrefix = $this->cleanUri(config('lapiv.base_route'));

        if (config('lapiv.default') === 'uri') {
            $newPrefix .= $this->cleanUri(config('lapiv.methods.uri.prefix'));
        }

        $newPrefix .= $this->cleanUri($prefix);

        return $newPrefix;
    }

    private function cleanUri($uri)
    {
        return Str::start(trim($uri, '/'), '/');
    }
}
