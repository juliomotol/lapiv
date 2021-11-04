<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class UriDriver extends BaseDriver
{
    public function routeGroup(Closure $callback): void
    {
        Route::prefix($this->options['prefix'])->group($callback);
    }

    public function getVersion(): string|int
    {
        return Request::route('version');
    }
}
