<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class UriDriver extends BaseDriver
{
    public function route(Closure $callback = null)
    {
        $router = Route::prefix($this->options['prefix']);

        return $callback
            ? $router->group($callback)
            : $router;
    }

    public function getVersion()
    {
        return Request::route('version');
    }
}
