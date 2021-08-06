<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class QueryStringDriver extends BaseDriver
{
    public function route(Closure $callback = null)
    {
        return $callback
            ? Route::prefix('/')->group($callback)
            : Route::prefix('/');
    }

    public function getVersion()
    {
        return Request::input($this->options['key']);
    }
}
