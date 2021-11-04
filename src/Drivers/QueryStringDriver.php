<?php

namespace JulioMotol\Lapiv\Drivers;

use Illuminate\Support\Facades\Request;

class QueryStringDriver extends BaseDriver
{
    public function getVersion(): string|int
    {
        return Request::input($this->options['key']);
    }
}
