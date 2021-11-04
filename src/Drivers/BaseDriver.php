<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;
use Illuminate\Support\Facades\Route;

abstract class BaseDriver
{
    public function __construct(public array $options = [])
    {
    }

    public function routeGroup(Closure $callback): void
    {
        Route::group([], $callback);
    }

    abstract public function getVersion(): string|int;
}
