<?php

namespace JulioMotol\Lapiv\Drivers;

use Closure;

abstract class BaseDriver
{
    protected array $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    abstract public function route(Closure $callback = null);

    abstract public function getVersion();
}
