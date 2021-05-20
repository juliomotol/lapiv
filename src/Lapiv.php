<?php

namespace JulioMotol\Lapiv;

use Illuminate\Support\Facades\Facade;

class Lapiv extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lapiv';
    }
}
