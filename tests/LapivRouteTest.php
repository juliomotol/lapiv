<?php

namespace JulioMotol\Lapiv\Tests;

use Illuminate\Support\Facades\Route;

class LapivRouteTest extends TestCase
{
    /** @test */
    public function it_can_register_api_versioned_routes_with_uri_method()
    {
        Route::lapiv('foo', 'Foo', function () {
            Route::get('/', function () {
                return 'lapiv';
            });
        });

        $response = $this->getJson('/v1/foo');

        $response->assertSuccessful();
    }

    /** @test */
    public function it_can_register_api_versioned_routes_with_query_string_method()
    {
        config(['lapiv.default' => 'query_string']);

        Route::lapiv('foo', 'Foo', function () {
            Route::get('/', function () {
                return 'lapiv';
            });
        });

        $response = $this->getJson('/foo');

        $response->assertSuccessful();
    }
}
