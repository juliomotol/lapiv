<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class QueryStringDriver extends BaseDriver
{
    public function route(Closure $callback = null)
    {
        $router = Route::prefix('/');

        return $callback
            ? $router->group($callback)
            : $router;
    }

    public function getVersion()
    {
        return Request::input($this->options['key']);
    }
}
