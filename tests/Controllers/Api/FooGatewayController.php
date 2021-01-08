<?php

namespace JulioMotol\Lapiv\Tests\Controllers\Api;

use JulioMotol\Lapiv\GatewayController;

class FooGatewayController extends GatewayController
{
    protected $apiControllers = [
        FooV1Controller::class,
    ];
}
