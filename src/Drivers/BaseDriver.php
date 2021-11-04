<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;

abstract class BaseDriver
{
    public function __construct(public array $options)
    {
    }

    abstract public function route(Closure $callback = null);

    abstract public function getVersion();
}
