<?php

namespace JulioMotol\Lapiv;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Routing\Router|\Illuminate\Routing\RouteRegistrar route()
 * @method static int getVersion()
 * 
 * @see \JulioMotol\Lapiv\ApiVersioningManager
 */
class Lapiv extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lapiv';
    }
}
