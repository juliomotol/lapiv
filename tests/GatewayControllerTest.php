<?php

namespace JulioMotol\Lapiv\Tests;

use BadMethodCallException;
use Closure;
use Illuminate\Support\Facades\Route;
use JulioMotol\Lapiv\Exceptions\InvalidArgumentException;
use JulioMotol\Lapiv\Exceptions\NotFoundApiVersionException;
use JulioMotol\Lapiv\Tests\Controllers\Api\Foo\FooV1Controller;

class GatewayControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'lapiv.base_namespace' => '\JulioMotol\Lapiv\Tests\Controllers\Api',
            'app.debug' => true,
        ]);
    }

    /** @test */
    public function it_can_handle_api_versioned_route_actions_with_uri_versioning_method()
    {
        $this->registerRoute();

        $response = $this->getJson('v1/foo');

        $response->assertSuccessful();
        $this->assertEquals(FooV1Controller::RESPONSE_MESSAGE, $response->getContent());
    }

    /** @test */
    public function it_can_handle_api_versioned_route_actions_with_query_string_versioning_method()
    {
        config(['lapiv.default' => 'query_string']);

        $this->registerRoute();

        $response = $this->getJson('foo?v=1');

        $response->assertSuccessful();
        $this->assertEquals(FooV1Controller::RESPONSE_MESSAGE, $response->getContent());
    }

    /** @test */
    public function it_can_handle_api_versioned_route_actions_with_header_versioning_method()
    {
        config(['lapiv.default' => 'header']);

        $this->registerRoute();

        $response = $this->getJson('foo', [
            'Accept' => 'application/vnd.laravel.v1+json',
        ]);

        $response->assertSuccessful();
        $this->assertEquals(FooV1Controller::RESPONSE_MESSAGE, $response->getContent());
    }

    /** @test */
    public function it_throws_exception_when_invalid_versioning_method_given()
    {
        config(['lapiv.default' => 'invalid']);

        $this->registerRoute();

        $response = $this->getJson('foo');

        $response->assertStatus(500);
        $this->assertEquals(InvalidArgumentException::class, $response->json('exception'));
    }

    /** @test */
    public function it_throws_exception_when_invalid_version_given()
    {
        $this->registerRoute();

        $response = $this->getJson('vasdf/foo');

        $response->assertStatus(500);
        $this->assertEquals(InvalidArgumentException::class, $response->json('exception'));
    }

    /** @test */
    public function it_throws_exception_when_version_not_found()
    {
        $this->registerRoute();

        $response = $this->getJson('v2/foo');

        $response->assertStatus(404);
        $this->assertEquals(NotFoundApiVersionException::class, $response->json('exception'));
    }

    /** @test */
    public function it_throws_exception_when_version_action_not_found()
    {
        $this->registerRoute(function () {
            Route::get('/bar', 'FooGatewayController@bar');
        });

        $response = $this->getJson('v1/foo/bar');

        $response->assertStatus(500);
        $this->assertEquals(BadMethodCallException::class, $response->json('exception'));
    }

    private function registerRoute(Closure $closure = null)
    {
        Route::lapiv('foo', 'Foo', function () use ($closure) {
            Route::get('/', 'FooGatewayController@index');

            if ($closure) {
                call_user_func($closure);
            }
        });
    }
}
