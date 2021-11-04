<?php

namespace JulioMotol\Lapiv\Tests;

use Illuminate\Support\Facades\Route;
use InvalidArgumentException;

class LapivRouteTest extends TestCase
{
    /** @var \Illuminate\Routing\Router */
    protected $router;

    protected function setUp(): void
    {
        parent::setUp();

        $this->router = $this->app['router'];
    }

    /** @test */
    public function it_can_register_grouped_api_routes_with_uri_method()
    {
        $routes = [];

        Route::lapiv(function () use (&$routes) {
            $routes[0] = Route::get('/foo', function () {
                return 'foo';
            })->name('foo');
            $routes[1] = Route::get('/bar', function () {
                return 'bar';
            })->name('bar');
        });

        foreach ($this->router->getRoutes() as $index => $registeredRoute) {
            $this->assertEquals($registeredRoute->uri(), $routes[$index]->uri());
            $this->assertEquals($registeredRoute->getName(), $routes[$index]->getName());
            $this->assertEquals($registeredRoute->getActionName(), $routes[$index]->getActionName());
        }
    }

    /** @test */
    public function it_can_register_grouped_api_routes_with_query_string_method()
    {
        config(['lapiv.default' => 'query_string']);

        $routes = [];

        Route::lapiv(function () use (&$routes) {
            $routes[0] = Route::get('/foo', function () {
                return 'foo';
            })->name('foo');
            $routes[1] = Route::get('/bar', function () {
                return 'bar';
            })->name('bar');
        });

        foreach ($this->router->getRoutes() as $index => $registeredRoute) {
            $this->assertEquals($registeredRoute->uri(), $routes[$index]->uri());
            $this->assertEquals($registeredRoute->getName(), $routes[$index]->getName());
            $this->assertEquals($registeredRoute->getActionName(), $routes[$index]->getActionName());
        }
    }

    /** @test */
    public function it_throws_exception_when_invalid_versioning_method_is_set()
    {
        $this->expectException(InvalidArgumentException::class);

        config(['lapiv.default' => 'foo']);

        Route::lapiv(fn () => Route::get('/', fn () => 'lapiv'));
    }
}
