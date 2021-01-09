<?php

namespace JulioMotol\Lapiv\Tests\Controllers\Api;

use Illuminate\Routing\Controller;

class FooV1Controller extends Controller
{
    const RESPONSE_MESSAGE = 'This is Foo V1';

    public function __invoke()
    {
        return response(self::RESPONSE_MESSAGE);
    }

    public function index()
    {
        return response(self::RESPONSE_MESSAGE);
    }
}
