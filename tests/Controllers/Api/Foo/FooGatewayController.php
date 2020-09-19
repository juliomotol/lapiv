<?php

namespace JulioMotol\Lapiv\Tests\Controllers\Api\Foo;

use JulioMotol\Lapiv\GatewayController;

class FooGatewayController extends GatewayController
{
    protected $apiControllers = [
        FooV1Controller::class,
    ];
}
