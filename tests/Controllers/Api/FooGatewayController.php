<?php

namespace JulioMotol\Lapiv\Tests\Controllers\Api;

use JulioMotol\Lapiv\GatewayController;

class FooGatewayController extends GatewayController
{
    protected array $apiControllers = [
        FooV1Controller::class,
    ];
}
