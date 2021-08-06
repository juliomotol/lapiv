<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class UriDriver extends BaseDriver
{
    public function route(Closure $callback = null)
    {
        return $callback
            ? Route::prefix($this->options['prefix'])->group($callback)
            : Route::prefix($this->options['prefix']);
    }

    public function getVersion()
    {
        return Request::route('version');
    }
}
