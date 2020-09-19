<?php

namespace JulioMotol\Lapiv\Tests\Controllers\Api\Foo;

use Illuminate\Routing\Controller;

class FooV1Controller extends Controller
{
    const RESPONSE_MESSAGE = 'This is Foo V1';

    public function index()
    {
        return response(self::RESPONSE_MESSAGE);
    }
}
