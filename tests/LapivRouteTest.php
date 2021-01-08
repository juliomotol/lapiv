<?php

namespace JulioMotol\Lapiv\Tests;

use Illuminate\Support\Facades\Route;

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
    public function it_can_register_api_routes_with_uri_method()
    {
        $route = Route::lapiv()->get('/', function () {
            return 'lapiv';
        })->name('uri-versioning');

        $registeredRoute = collect($this->router->getRoutes())->first();

        $this->assertEquals($registeredRoute->uri(), $route->uri());
        $this->assertEquals($registeredRoute->getName(), $route->getName());
        $this->assertEquals($registeredRoute->getActionName(), $route->getActionName());
    }

    /** @test */
    public function it_can_register_grouped_api_routes_with_uri_method()
    {
        $routes = [];

        $route = Route::lapiv(function () use (&$routes) {
            $routes[0] = Route::get('/foo', function () {
                return 'foo';
            })->name('foo');
            $routes[1] = Route::get('/bar', function () {
                return 'bar';
            })->name('bar');
        });

        $registeredRoutes = collect($this->router->getRoutes())->all();

        foreach ($registeredRoutes as $index => $registeredRoute) {
            $this->assertEquals($registeredRoute->uri(), $routes[$index]->uri());
            $this->assertEquals($registeredRoute->getName(), $routes[$index]->getName());
            $this->assertEquals($registeredRoute->getActionName(), $routes[$index]->getActionName());
        }
    }

    /** @test */
    public function it_can_register_api_routes_with_query_string_method()
    {
        config(['lapiv.default' => 'query_string']);

        $route = Route::lapiv()->get('/', function () {
            return 'lapiv';
        })->name('query-string-versioning');

        $registeredRoute = collect($this->router->getRoutes())->first();

        $this->assertEquals($registeredRoute->uri(), $route->uri());
        $this->assertEquals($registeredRoute->getName(), $route->getName());
        $this->assertEquals($registeredRoute->getActionName(), $route->getActionName());
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

        $registeredRoutes = collect($this->router->getRoutes())->all();

        foreach ($registeredRoutes as $index => $registeredRoute) {
            $this->assertEquals($registeredRoute->uri(), $routes[$index]->uri());
            $this->assertEquals($registeredRoute->getName(), $routes[$index]->getName());
            $this->assertEquals($registeredRoute->getActionName(), $routes[$index]->getActionName());
        }
    }
}
